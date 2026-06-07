<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('BACKEND_API_URL');
    }

    public function adminIndex()
    {
        $token = session('api_token');
        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/admin/statistics');
            $stats = $response->successful() ? $response->json() : [
                'total_users' => 0,
                'total_sellers' => 0,
                'total_products' => 0,
                'total_orders' => 0,
                'total_transactions' => 0,
                'total_revenue' => 0,
                'growth' => 0,
                'recent_transactions' => []
            ];
        } catch (\Exception $e) {
            $stats = [
                'total_users' => 0,
                'total_sellers' => 0,
                'total_products' => 0,
                'total_orders' => 0,
                'total_transactions' => 0,
                'total_revenue' => 0,
                'growth' => 0,
                'recent_transactions' => []
            ];
        }

        return view('admin.dashboard', $stats);
    }

    public function adminUsers(Request $request)
    {
        $token = session('api_token');
        $search = $request->query('search');
        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/admin/users', [
                'search' => $search
            ]);
            $users = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $users = [];
        }
        return view('admin.users', compact('users'));
    }



    public function adminUserShow($id)
    {
        $token = session('api_token');
        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . "/admin/users/{$id}");
            if (!$response->successful()) {
                return redirect()->route('admin.users')->with('error', 'User tidak ditemukan');
            }
            $user = $response->json();
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Terjadi kesalahan sistem');
        }
        return view('admin.users_show', compact('user'));
    }

    public function deleteUser($id)
    {
        $token = session('api_token');
        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . "/admin/users/{$id}");
            if ($response->successful()) {
                return back()->with('success', 'User berhasil dihapus');
            }
            return back()->with('error', 'Gagal menghapus user');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem');
        }
    }



    public function adminProducts(Request $request)
    {
        $token = session('api_token');
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $perPage = 10;

        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/admin/products');
            $productsList = $response->successful() ? $response->json() : [];

            // Get stats too
            $statsResponse = Http::withToken($token)->get($this->apiBaseUrl . '/admin/statistics');
            $stats = $statsResponse->successful() ? $statsResponse->json() : [];
        } catch (\Exception $e) {
            $productsList = [];
            $stats = [];
        }

        // Saring berdasarkan search query jika ada
        if ($search) {
            $searchLower = strtolower($search);
            $productsList = array_values(array_filter($productsList, function($product) use ($searchLower) {
                $nameMatch = str_contains(strtolower($product['name'] ?? ''), $searchLower);
                $sellerMatch = str_contains(strtolower($product['seller']['name'] ?? ''), $searchLower);
                return $nameMatch || $sellerMatch;
            }));
        }

        // Paginasi manual
        $total = count($productsList);
        $slicedProducts = array_slice($productsList, ($page - 1) * $perPage, $perPage);

        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedProducts,
            $total,
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('admin.products', compact('products', 'stats', 'search'));
    }

    public function adminProductShow($id)
    {
        $token = session('api_token');
        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/products/' . $id);
            $product = $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            $product = null;
        }

        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan');
        }

        return view('admin.products_show', compact('product'));
    }

    public function adminTransactions(Request $request)
    {
        $token = session('api_token');
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $perPage = 10;

        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/admin/orders');
            $ordersList = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $ordersList = [];
        }

        // Saring berdasarkan search query jika ada
        if ($search) {
            $searchLower = strtolower($search);
            $ordersList = array_values(array_filter($ordersList, function($order) use ($searchLower) {
                $orderId = '#ORD-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
                $idMatch = str_contains(strtolower($orderId), $searchLower);
                $buyerMatch = str_contains(strtolower($order['buyer']['name'] ?? ''), $searchLower);
                $sellerMatch = str_contains(strtolower($order['seller']['name'] ?? ''), $searchLower);
                return $idMatch || $buyerMatch || $sellerMatch;
            }));
        }

        // Paginasi manual
        $total = count($ordersList);
        $slicedOrders = array_slice($ordersList, ($page - 1) * $perPage, $perPage);

        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedOrders,
            $total,
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        // Keep all orders count and sums for stats (using full unfiltered list for correct statistics)
        $allOrders = $ordersList; // fallback or we can keep a backup of the original unfitered list for statistics?
        // Wait, for correct statistics, we should calculate stats from the original unfiltered list (without search filter).
        // Let's get the original list first
        try {
            $unfilteredOrders = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $unfilteredOrders = [];
        }

        return view('admin.transactions', compact('orders', 'unfilteredOrders', 'search'));
    }

    public function adminTransactionShow($id)
    {
        $token = session('api_token');
        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/orders/' . $id);
            $order = $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            $order = null;
        }

        if (!$order) {
            return redirect()->route('admin.transactions')->with('error', 'Transaksi tidak ditemukan');
        }

        return view('admin.transactions_show', compact('order'));
    }

    public function adminWithdrawals(Request $request)
    {
        $token = session('api_token');
        $page = $request->query('page', 1);
        $perPage = 10;

        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/admin/withdrawals');
            $withdrawalsList = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $withdrawalsList = [];
        }

        // Get completed orders for statistics
        try {
            $ordersResponse = Http::withToken($token)->get($this->apiBaseUrl . '/admin/orders');
            $ordersList = $ordersResponse->successful() ? $ordersResponse->json() : [];
        } catch (\Exception $e) {
            $ordersList = [];
        }

        $completedOrders = collect($ordersList)->filter(function ($order) {
            return in_array(strtolower($order['status']), ['completed', 'success']);
        });

        $totalCompletedRevenue = $completedOrders->sum('final_price');

        $completedWithdrawals = collect($withdrawalsList)->filter(function ($w) {
            return strtolower($w['status']) === 'completed';
        });
        $totalCompletedWithdrawals = $completedWithdrawals->sum('amount_raw');

        // Escrow Balance = Total completed orders revenue - Total completed withdrawals
        $escrowBalance = $totalCompletedRevenue - $totalCompletedWithdrawals;

        // Platform Fees (2% of completed orders)
        $platformFeeRate = 0.02;
        $platformRevenue = $totalCompletedRevenue * $platformFeeRate;

        // Sellers Net Balance = Escrow balance - Platform revenue
        $sellersBalance = $escrowBalance - $platformRevenue;

        // Format stats values
        $stats = [
            'escrow_balance' => 'Rp ' . number_format($escrowBalance, 0, ',', '.'),
            'platform_revenue' => 'Rp ' . number_format($platformRevenue, 0, ',', '.'),
            'sellers_balance' => 'Rp ' . number_format($sellersBalance, 0, ',', '.'),
        ];

        // Paginasi manual
        $total = count($withdrawalsList);
        $slicedWithdrawals = array_slice($withdrawalsList, ($page - 1) * $perPage, $perPage);

        $withdrawals = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedWithdrawals,
            $total,
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('admin.withdrawals', compact('withdrawals', 'stats'));
    }

    public function adminApproveWithdrawal(Request $request, $id)
    {
        $request->validate([
            'proof_image' => 'required|image|max:2048',
            'admin_note' => 'nullable|string'
        ]);

        $token = session('api_token');
        $http = Http::withToken($token);

        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $http = $http->attach(
                'proof_image',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            );
        }

        try {
            $response = $http->post($this->apiBaseUrl . "/admin/withdrawals/{$id}/approve", [
                'admin_note' => $request->admin_note
            ]);

            if ($response->successful()) {
                return redirect()->route('admin.withdrawals')->with('success', 'Penarikan saldo berhasil disetujui!');
            }

            return back()->with('error', $response->json()['message'] ?? 'Gagal menyetujui penarikan saldo');
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal');
        }
    }

    public function adminRejectWithdrawal(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string',
            'proof_image' => 'nullable|image|max:2048',
            'admin_note' => 'nullable|string'
        ]);

        $token = session('api_token');
        $http = Http::withToken($token);

        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $http = $http->attach(
                'proof_image',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            );
        }

        try {
            $response = $http->post($this->apiBaseUrl . "/admin/withdrawals/{$id}/reject", [
                'rejected_reason' => $request->rejected_reason,
                'admin_note' => $request->admin_note
            ]);

            if ($response->successful()) {
                return redirect()->route('admin.withdrawals')->with('success', 'Penarikan saldo telah berhasil ditolak');
            }

            return back()->with('error', $response->json()['message'] ?? 'Gagal menolak penarikan saldo');
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal');
        }
    }

    public function adminDeleteProduct($id)
    {
        $token = session('api_token');
        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . "/admin/products/{$id}");
            if ($response->successful()) {
                return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus secara paksa');
            }
            return back()->with('error', 'Gagal menghapus produk');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem');
        }
    }

    public function adminSettings()
    {
        return view('admin.settings');
    }
    public function adminChats()
    {
        return view('admin.chats');
    }

    // Seller Methods
    public function sellerIndex()
    {
        $token = session('api_token');
        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/seller/statistics');
            $stats = $response->successful() ? $response->json() : [
                'total_products' => 0,
                'pending_orders' => 0,
                'completed_orders' => 0,
                'revenue' => 'Rp 0',
                'growth' => 0,
                'recent_orders' => [],
                'top_products' => [],
                'chart' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => [0, 0, 0, 0, 0, 0]
                ]
            ];

            // Fix image URLs in top products
            if (isset($stats['top_products'])) {
                $stats['top_products'] = collect($stats['top_products'])->map(function($product) {
                    $image = $product['image'] ?? null;
                    if ($image && !Str::startsWith($image, 'http')) {
                        $image = (Str::startsWith($image, 'storage/') 
                            ? env('BACKEND_URL') . '/' . $image 
                            : env('BACKEND_URL') . '/storage/' . $image);
                    }
                    $product['image'] = $image;
                    return $product;
                })->toArray();
            }

            // Fix image URLs in recent chats
            if (isset($stats['recent_chats'])) {
                $stats['recent_chats'] = collect($stats['recent_chats'])->map(function($chat) {
                    $avatar = $chat['avatar'] ?? null;
                    if ($avatar && !Str::startsWith($avatar, 'http')) {
                        $avatar = (Str::startsWith($avatar, 'storage/') 
                            ? env('BACKEND_URL') . '/' . $avatar 
                            : env('BACKEND_URL') . '/storage/' . $avatar);
                    }
                    $chat['avatar'] = $avatar;
                    return $chat;
                })->toArray();
            }
        } catch (\Exception $e) {
            $stats = [
                'total_products' => 0,
                'pending_orders' => 0,
                'completed_orders' => 0,
                'revenue' => 'Rp 0',
                'growth' => 0,
                'recent_orders' => [],
                'top_products' => [],
                'chart' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => [0, 0, 0, 0, 0, 0]
                ]
            ];
        }

        return view('seller.dashboard', $stats);
    }

    public function sellerProducts(Request $request)
    {
        $token = session('api_token');
        $page = $request->query('page', 1);
        $search = $request->query('search');
        $category = $request->query('category');
        $status = $request->query('status');

        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/seller/products', [
                'page' => $page,
                'search' => $search,
                'category' => $category,
                'status' => $status
            ]);

            $data = $response->successful() ? $response->json() : null;

            if ($data && isset($data['products']['data'])) {
                $productsList = collect($data['products']['data'])->map(function($product) {
                    $image = $product['image'] ?? null;
                    if ($image && !Str::startsWith($image, 'http')) {
                        $image = (Str::startsWith($image, 'storage/') 
                            ? env('BACKEND_URL') . '/' . $image 
                            : env('BACKEND_URL') . '/storage/' . $image);
                    }
                    $product['image'] = $image;
                    return $product;
                })->toArray();

                $products = new \Illuminate\Pagination\LengthAwarePaginator(
                    $productsList,
                    $data['products']['total'],
                    $data['products']['per_page'],
                    $data['products']['current_page'],
                    ['path' => url()->current(), 'query' => $request->query()]
                );
                $categories = $data['categories'] ?? [];
            } else {
                $products = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
                $categories = [];
            }

        } catch (\Exception $e) {
            $products = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $categories = [];
        }

        return view('seller.products', compact('products', 'categories', 'search', 'category', 'status'));
    }

    public function sellerAddProduct()
    {
        $token = session('api_token');
        try {
            $response = Http::get($this->apiBaseUrl . '/categories');
            $categories = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $categories = [];
        }
        return view('seller.add_product', compact('categories'));
    }

    public function sellerStoreProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $token = session('api_token');
        $http = Http::withToken($token);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $http = $http->attach(
                'image',
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalName()
            );
        }

        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $index => $file) {
                $http = $http->attach(
                    "additional_images[$index]",
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }
        }

        try {
            $response = $http->post($this->apiBaseUrl . '/products', [
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock
            ]);

            if ($response->successful()) {
                return redirect()->route('seller.products')
                    ->with('success', 'Product berhasil ditambahkan');
            }

            return back()->with('error', $response->json()['message'] ?? 'Gagal menambahkan produk');
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal');
        }
    }

    public function sellerEditProduct($id)
    {
        $token = session('api_token');

        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/products/' . $id);
            $product = $response->successful() ? $response->json() : null;

            // Ambil kategori juga
            $catResponse = Http::get($this->apiBaseUrl . '/categories');
            $categories = $catResponse->successful() ? $catResponse->json() : [];
        } catch (\Exception $e) {
            $product = null;
            $categories = [];
        }

        if (!$product) {
            return redirect()->route('seller.products')->with('error', 'Produk tidak ditemukan');
        }

        return view('seller.edit_product', compact('product', 'categories'));
    }

    public function sellerUpdateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $token = session('api_token');
        $http = Http::withToken($token);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $http = $http->attach(
                'image',
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalName()
            );
        }

        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $index => $file) {
                $http = $http->attach(
                    "additional_images[$index]",
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }
        }

        try {
            $fields = [
                '_method' => 'PUT',
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock
            ];

            if ($request->delete_images) {
                $fields['delete_images'] = $request->delete_images;
            }

            $response = $http->post($this->apiBaseUrl . '/products/' . $id, $fields);

            if ($response->successful()) {
                return redirect()->route('seller.products')
                    ->with('success', 'Produk berhasil diupdate');
            }

            return back()->with('error', $response->json()['message'] ?? 'Gagal update produk');
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal');
        }
    }

    public function sellerDeleteProduct($id)
    {
        $token = session('api_token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/products/' . $id);

            if ($response->successful()) {
                return redirect()->route('seller.products')
                    ->with('success', 'Produk berhasil dihapus');
            }

            return back()->with('error', $response->json()['message'] ?? 'Gagal hapus produk');
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal');
        }
    }
    public function sellerOrders(Request $request)
    {
        $token = session('api_token');
        $page = $request->query('page', 1);
        $search = $request->query('search');
        $status = $request->query('status');
        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/seller-orders', [
                'page' => $page,
                'search' => $search,
                'status' => $status
            ]);
            $ordersData = $response->successful() ? $response->json() : null;

            if ($ordersData) {
                $mappedData = collect($ordersData['data'])->map(function ($order) {
                    $avatar = $order['buyer']['profile_image'] ?? $order['buyer']['avatar'] ?? null;
                    $buyerAvatarUrl = null;
                    if ($avatar) {
                        $buyerAvatarUrl = \Illuminate\Support\Str::startsWith($avatar, 'http') 
                            ? $avatar 
                            : (\Illuminate\Support\Str::startsWith($avatar, 'storage/') 
                                ? env('BACKEND_URL') . '/' . $avatar 
                                : env('BACKEND_URL') . '/storage/' . $avatar);
                    }

                    return [
                        'id' => $order['id'],
                        'order_number' => '#ORD-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                        'buyer_name' => $order['buyer']['name'] ?? 'Pembeli Umum',
                        'buyer_id' => $order['buyer_id'] ?? null,
                        'buyer_avatar' => $buyerAvatarUrl,
                        'products_count' => count($order['items'] ?? []),
                        'total_price' => 'Rp ' . number_format($order['final_price'] ?? 0, 0, ',', '.'),
                        'status' => $order['status'],
                        'date' => \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i'),
                        'items' => collect($order['items'] ?? [])->map(function ($item) {
                            return [
                                'name' => $item['product']['name'] ?? 'Produk Dihapus',
                                'quantity' => $item['quantity']
                            ];
                        })->toArray()
                    ];
                })->toArray();

                // Manual pagination object for Blade
                $orders = new \Illuminate\Pagination\LengthAwarePaginator(
                    $mappedData,
                    $ordersData['total'],
                    $ordersData['per_page'],
                    $ordersData['current_page'],
                    ['path' => url()->current(), 'query' => $request->query()]
                );
            } else {
                $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            }
        } catch (\Exception $e) {
            $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }
        return view('seller.orders', compact('orders'));
    }
    public function sellerOrderShow($id)
    {
        $token = session('api_token');
        $cleanId = preg_replace('/[^0-9]/', '', $id);
        
        try {
            // SYNC OTOMATIS: Tembak check-status dulu biar database update dari DompetX
            Http::withToken($token)->get($this->apiBaseUrl . '/payment/check-status/' . $cleanId);

            // Baru ambil data ordernya
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/orders/' . $cleanId);
            
            if (!$response->successful()) {
                return redirect()->route('seller.orders')->with('error', 'Pesanan tidak ditemukan');
            }

            $order = $response->json();
            return view('seller.order_detail', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('seller.orders')->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }
    public function sellerUpdateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processed,shipped,completed'
        ]);

        $token = session('api_token');
        try {
            $response = Http::withToken($token)->put($this->apiBaseUrl . '/orders/' . $id . '/status', [
                'status' => $request->status
            ]);

            if ($response->successful()) {
                $msg = 'Status pesanan berhasil diperbarui!';
                if ($request->status === 'shipped') {
                    $msg = 'Pesanan berhasil dikirim!';
                } elseif ($request->status === 'completed') {
                    $msg = 'Pesanan berhasil diselesaikan!';
                } elseif ($request->status === 'processed') {
                    $msg = 'Pesanan sedang diproses!';
                }
                return redirect()->route('seller.orders.show', $id)->with('success', $msg);
            }

            return back()->with('error', $response->json()['message'] ?? 'Gagal memperbarui status pesanan');
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal');
        }
    }
    public function sellerChats()
    {
        return view('seller.chats');
    }
    public function sellerTransactions(Request $request)
    {
        $token = session('api_token');
        $page = $request->query('page', 1);
        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/seller/transactions', [
                'page' => $page
            ]);
            $data = $response->successful() ? $response->json() : null;

            if ($data) {
                $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
                    $data['transactions']['data'],
                    $data['transactions']['total'],
                    $data['transactions']['per_page'],
                    $data['transactions']['current_page'],
                    [
                        'path' => url()->current(),
                        'pageName' => 'page'
                    ]
                );
                $transactions->withQueryString();

                $stats = $data['stats'];
                $withdrawals = $data['withdrawals'];
            } else {
                $transactions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1, ['path' => url()->current(), 'pageName' => 'page']);
                $transactions->withQueryString();
                $stats = [
                    'total_revenue' => 'Rp 0', 
                    'pending_revenue' => 'Rp 0', 
                    'platform_fees' => 'Rp 0',
                    'withdrawable_balance' => 'Rp 0',
                    'withdrawable_balance_raw' => 0,
                    'total_withdrawn' => 'Rp 0'
                ];
                $withdrawals = [];
            }
        } catch (\Exception $e) {
            $transactions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1, ['path' => url()->current(), 'pageName' => 'page']);
            $transactions->withQueryString();
            $stats = [
                'total_revenue' => 'Rp 0', 
                'pending_revenue' => 'Rp 0', 
                'platform_fees' => 'Rp 0',
                'withdrawable_balance' => 'Rp 0',
                'withdrawable_balance_raw' => 0,
                'total_withdrawn' => 'Rp 0'
            ];
            $withdrawals = [];
        }
        return view('seller.transactions', compact('transactions', 'stats', 'withdrawals'));
    }

    public function sellerWithdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string'
        ]);

        $token = session('api_token');
        try {
            $response = Http::withToken($token)->post($this->apiBaseUrl . '/seller/withdraw', [
                'amount' => $request->amount,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name
            ]);

            if ($response->successful()) {
                return redirect()->route('seller.transactions')->with('success', 'Permintaan penarikan saldo berhasil diajukan!');
            }

            return back()->with('error', $response->json()['message'] ?? 'Gagal mengajukan penarikan saldo');
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal');
        }
    }
    public function sellerReviews(Request $request)
    {
        $token = session('api_token');
        $page = $request->query('page', 1);
        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/seller/reviews', [
                'page' => $page
            ]);
            $data = $response->successful() ? $response->json() : [
                'stats' => ['total' => 0, 'five_star' => 0, 'average' => 0, 'distribution' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0]],
                'reviews' => []
            ];

            if (isset($data['reviews']['data'])) {
                $reviewsList = $data['reviews']['data'];
                $reviews = new \Illuminate\Pagination\LengthAwarePaginator(
                    $reviewsList,
                    $data['reviews']['total'],
                    $data['reviews']['per_page'],
                    $data['reviews']['current_page'],
                    ['path' => url()->current(), 'query' => $request->query()]
                );
            } else {
                $reviews = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
            }
        } catch (\Exception $e) {
            $data = [
                'stats' => ['total' => 0, 'five_star' => 0, 'average' => 0, 'distribution' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0]],
                'reviews' => []
            ];
            $reviews = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
        }

        return view('seller.reviews', [
            'stats' => $data['stats'],
            'reviews' => $reviews
        ]);
    }
    public function sellerSettings()
    {
        return view('seller.settings');
    }

    public function sellerReplyReview(Request $request, $id)
    {
        $token = session('api_token');
        try {
            $response = Http::withToken($token)->post($this->apiBaseUrl . "/seller/reviews/{$id}/reply", [
                'reply' => $request->reply
            ]);

            if ($response->successful()) {
                return back()->with('success', 'Berhasil membalas ulasan');
            }

            return back()->with('error', $response->json()['message'] ?? 'Gagal membalas ulasan');
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal');
        }
    }

    public function sellerDestroyProduct($id)
    {
        $token = session('api_token');
        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/products/' . $id);
            if ($response->successful()) {
                return redirect()->route('seller.products')->with('success', 'Produk Zeven berhasil dihapus!');
            }
            return back()->with('error', $response->json()['message'] ?? 'Gagal menghapus produk');
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke API Backend gagal');
        }
    }
}
