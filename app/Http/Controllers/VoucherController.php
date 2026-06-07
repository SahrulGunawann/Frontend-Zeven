<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class VoucherController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('BACKEND_API_URL');
    }

    public function index(Request $request)
    {
        $token = Session::get('api_token');
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $page = $request->query('page', 1);
        $perPage = 10;

        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/vouchers');

            // Log for debugging if empty
            if ($response->failed()) {
                \Log::error('Voucher API Failed: ' . $response->body());
            }

            $vouchersList = $response->successful() && is_array($response->json()) ? $response->json() : [];
        } catch (\Exception $e) {
            \Log::error('Voucher Controller Error: ' . $e->getMessage());
            $vouchersList = [];
        }

        // Hitung statistik riil sebelum disaring oleh search/status
        $totalCount = count($vouchersList);
        $activeCount = 0;
        $expiredCount = 0;
        $fullCount = 0;

        foreach ($vouchersList as $v) {
            $endDate = !empty($v['end_date']) ? Carbon::parse($v['end_date']) : null;
            $isExpired = $endDate ? $endDate->isPast() : false;
            $isFull = ($v['used'] ?? 0) >= ($v['quota'] ?? 0);

            if ($isExpired) {
                $expiredCount++;
            } elseif ($isFull) {
                $fullCount++;
            } else {
                $activeCount++;
            }
        }

        // Saring berdasarkan status filter jika bukan 'all'
        if ($status !== 'all') {
            $vouchersList = array_values(array_filter($vouchersList, function($v) use ($status) {
                $endDate = !empty($v['end_date']) ? Carbon::parse($v['end_date']) : null;
                $isExpired = $endDate ? $endDate->isPast() : false;
                $isFull = ($v['used'] ?? 0) >= ($v['quota'] ?? 0);

                if ($status === 'active') {
                    return !$isExpired && !$isFull;
                } elseif ($status === 'expired') {
                    return $isExpired;
                } elseif ($status === 'full') {
                    return !$isExpired && $isFull;
                }
                return true;
            }));
        }

        // Saring berdasarkan search query jika ada
        if ($search) {
            $searchLower = strtolower($search);
            $vouchersList = array_values(array_filter($vouchersList, function($voucher) use ($searchLower) {
                return str_contains(strtolower($voucher['code'] ?? ''), $searchLower);
            }));
        }

        // Paginasi manual
        $total = count($vouchersList);
        $slicedVouchers = array_slice($vouchersList, ($page - 1) * $perPage, $perPage);

        $vouchers = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedVouchers,
            $total,
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('admin.vouchers', compact(
            'vouchers',
            'search',
            'status',
            'totalCount',
            'activeCount',
            'expiredCount',
            'fullCount'
        ));
    }

    public function create()
    {
        return view('admin.vouchers_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'discount_percent' => 'required|numeric',
            'max_discount' => 'required|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
            'quota' => 'required|numeric'
        ]);

        $token = Session::get('api_token');

        try {
            $response = Http::withToken($token)->post($this->apiBaseUrl . '/vouchers', $request->all());

            if ($response->successful()) {
                return redirect('/admin/vouchers')->with('success', 'Voucher berhasil dibuat!');
            }

            $message = $response->json('message') ?? 'Gagal membuat voucher (API Error).';
            return redirect('/admin/vouchers')->with('error', $message);
        } catch (\Exception $e) {
            return redirect('/admin/vouchers')->with('error', 'Koneksi ke API Backend gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $token = Session::get('api_token');
        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/vouchers/' . $id);

            if ($response->successful()) {
                return redirect('/admin/vouchers')->with('success', 'Voucher berhasil dihapus!');
            }

            $message = $response->json('message') ?? 'Gagal menghapus voucher (API Error).';
            return redirect('/admin/vouchers')->with('error', $message);
        } catch (\Exception $e) {
            return redirect('/admin/vouchers')->with('error', 'Koneksi ke API Backend gagal: ' . $e->getMessage());
        }
    }
}