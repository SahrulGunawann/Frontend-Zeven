<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('BACKEND_API_URL');
    }

    public function updateProfile(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'current_password' => 'required_with:password',
            'password' => 'nullable|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama lengkap / nama toko wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'current_password.required_with' => 'Kata sandi lama wajib diisi jika ingin mengganti kata sandi baru.',
            'password.min' => 'Kata sandi baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $token = Session::get('api_token');

        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $payload['current_password'] = $request->current_password;
            $payload['password'] = $request->password;
            $payload['password_confirmation'] = $request->password_confirmation;
        }

        try {
            $response = Http::withToken($token)->put($this->apiBaseUrl . '/profile', $payload);

            if ($response->successful()) {
                $updatedUser = $response->json('user');

                // Pertahankan avatar_url agar foto profil tidak hilang setelah update
                if (array_key_exists('profile_image', $updatedUser)) {
                    if (!empty($updatedUser['profile_image'])) {
                        if (str_starts_with($updatedUser['profile_image'], 'http')) {
                            $updatedUser['avatar_url'] = $updatedUser['profile_image'];
                        } else {
                            $backendUrl = env('BACKEND_URL', 'http://192.168.100.9:8001');
                            $updatedUser['avatar_url'] = $backendUrl . '/storage/' . $updatedUser['profile_image'];
                        }
                    } else {
                        $updatedUser['avatar_url'] = null;
                    }
                } else {
                    $updatedUser['avatar_url'] = Session::get('user.avatar_url');
                }

                Session::put('user', $updatedUser);

                return back()->with('success', 'Profil berhasil diperbarui!');
            }

            return back()->with('error', 'Gagal memperbarui: ' . ($response->json()['message'] ?? 'Terjadi kesalahan.'));

        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal.');
        }
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $token = Session::get('api_token');

        try {
            $response = Http::withToken($token)
                ->attach('avatar', file_get_contents($request->file('avatar')->getRealPath()), $request->file('avatar')->getClientOriginalName())
                ->post($this->apiBaseUrl . '/profile/avatar');

            if ($response->successful()) {
                $data = $response->json();
                $updatedUser = $data['user'];

                // Simpan full URL avatar agar bisa ditampilkan di Frontend
                if (str_starts_with($updatedUser['profile_image'], 'http')) {
                    $updatedUser['avatar_url'] = $updatedUser['profile_image'];
                } else {
                    $backendUrl = env('BACKEND_URL', 'http://192.168.100.9:8001');
                    $updatedUser['avatar_url'] = $backendUrl . '/storage/' . $updatedUser['profile_image'];
                }

                Session::put('user', $updatedUser);

                return back()->with('success', 'Foto profil berhasil diperbarui!');
            }

            return back()->with('error', 'Gagal mengupload foto: ' . ($response->json()['message'] ?? 'Terjadi kesalahan.'));

        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal.');
        }
    }

    public function deleteAvatar(Request $request)
    {
        $token = Session::get('api_token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/profile/avatar');

            if ($response->successful()) {
                $data = $response->json();
                $updatedUser = $data['user'];
                $updatedUser['avatar_url'] = null;

                Session::put('user', $updatedUser);

                return back()->with('success', 'Foto profil berhasil dihapus!');
            }

            return back()->with('error', 'Gagal menghapus foto profil: ' . ($response->json()['message'] ?? 'Terjadi kesalahan.'));

        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal.');
        }
    }
    public function deleteAccount(Request $request)
    {
        $token = Session::get('api_token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/profile');

            if ($response->successful()) {
                Session::flush();
                return redirect()->route('login')->with('success', 'Akun Anda berhasil dihapus secara permanen.');
            }

            return back()->with('error', $response->json()['message'] ?? 'Terjadi kesalahan sistem saat menghapus akun.');

        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal.');
        }
    }
}
