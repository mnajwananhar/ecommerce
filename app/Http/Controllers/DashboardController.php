<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\SellerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import Log facade
use Carbon\Carbon;

class DashboardController extends Controller
{
  public function adminDashboard()
  {
    // Ambil statistik pengguna
    $totalUsers = User::count();
    $totalSellers = User::where('role', 'seller')->count();
    $totalCustomers = User::where('role', 'customer')->count();
    $totalProducts = Product::count();
    $totalOrders = Order::count();
    $pendingSellerRequests = SellerRequest::where('status', 'pending')->count();

    // Ambil total revenue dan revenue per bulan
    $totalRevenue = Order::where('status', 'accepted')->sum('total_price');
    $monthlyRevenue = Order::where('status', 'accepted')
      ->whereMonth('created_at', Carbon::now()->month)
      ->sum('total_price');

    // Ambil daftar orderan terbaru
    $recentOrders = Order::with(['user'])
      ->where('status', 'accepted')
      ->latest()
      ->take(5)
      ->get();

    // Debugging untuk memastikan recentOrders tidak kosong
    Log::info('Recent Orders:', $recentOrders->toArray());

    // Ambil statistik order bulanan
    $monthlyOrders = Order::select(
      DB::raw('MONTH(created_at) as month'),
      DB::raw('COUNT(*) as count'),
      DB::raw('COALESCE(SUM(total_price), 0) as revenue')
    )
      ->where('status', 'accepted')
      ->whereYear('created_at', Carbon::now()->year)
      ->groupBy(DB::raw('MONTH(created_at)'))
      ->orderBy('month')
      ->get();

    // Pastikan ada data untuk setiap bulan (1 - 12)
    $completeMonthlyData = collect(range(1, 12))->map(function ($month) use ($monthlyOrders) {
      $monthData = $monthlyOrders->firstWhere('month', $month);
      return [
        'month' => $month,
        'count' => $monthData ? (int)$monthData->count : 0,
        'revenue' => $monthData ? (float)$monthData->revenue : 0
      ];
    });

    // Ambil produk terlaris
    $topProducts = Product::withCount(['orderDetails as total_sold' => function ($query) {
      $query->whereHas('order', function ($q) {
        $q->where('status', 'accepted');
      });
    }])
      ->orderBy('total_sold', 'desc')
      ->take(5)
      ->get();

    return view('dashboard.admin', compact(
      'totalUsers',
      'totalSellers',
      'totalCustomers',
      'totalProducts',
      'totalOrders',
      'pendingSellerRequests',
      'totalRevenue',
      'monthlyRevenue',
      'completeMonthlyData',
      'topProducts',
      'recentOrders' // Pastikan variabel ini dikirim ke view
    ));
  }
  public function sellerDashboard()
  {
    $sellerId = auth()->guard('web')->user() ? auth()->guard('web')->user()->id : null;

    if (!$sellerId) {
      return redirect()->route('login');
    }

    // Initialize default values
    $totalProducts = 0;
    $totalOrders = 0;
    $monthlyRevenue = 0;
    $totalRevenue = 0;
    $averageRating = 0;

    try {
        $totalProducts = Product::where('seller_id', $sellerId)->count();
        $totalOrders = Order::whereHas('details.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->count();

        // Fix Monthly Revenue Calculation
        $monthlyRevenue = Order::query()
          ->join('order_details', 'orders.id', '=', 'order_details.order_id')
          ->join('products', 'order_details.product_id', '=', 'products.id')
          ->where('orders.status', 'accepted') // Ganti 'completed' dengan 'accepted'
          ->where('products.seller_id', $sellerId)
          ->whereBetween('orders.created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
          ])
          ->sum(DB::raw('order_details.quantity * order_details.price'));

        // Get recent orders for this seller
        $recentOrders = Order::whereHas('details.product', function ($query) use ($sellerId) {
          $query->where('seller_id', $sellerId);
        })
          ->with(['user', 'details.product'])
          ->latest()
          ->take(5)
          ->get();

        // Update monthly orders query for better chart data
        $monthlyOrdersData = Order::whereHas('details.product', function ($query) use ($sellerId) {
          $query->where('seller_id', $sellerId);
        })
          ->select(
            DB::raw('MONTH(orders.created_at) as month'),
            DB::raw('COUNT(DISTINCT orders.id) as count'),
            DB::raw('COALESCE(SUM(order_details.quantity * order_details.price), 0) as revenue')
          )
          ->join('order_details', 'orders.id', '=', 'order_details.order_id')
          ->join('products', function ($join) use ($sellerId) {
            $join->on('order_details.product_id', '=', 'products.id')
              ->where('products.seller_id', '=', $sellerId);
          })
          ->where('orders.status', 'accepted') // Ganti 'completed' dengan 'accepted'
          ->whereYear('orders.created_at', Carbon::now()->year)
          ->groupBy('month')
          ->orderBy('month')
          ->get();

        // Pastikan ada data untuk setiap bulan
        $completeMonthlyData = collect(range(1, 12))->map(function ($month) use ($monthlyOrdersData) {
          $monthData = $monthlyOrdersData->firstWhere('month', $month);
          return [
            'month' => $month,
            'count' => $monthData ? (int)$monthData->count : 0,
            'revenue' => $monthData ? (float)$monthData->revenue : 0
          ];
        });

        // Hitung total revenue dari data bulanan
        $totalRevenue = collect($completeMonthlyData)->sum(fn($item) => (float) $item['revenue']);

        // Get top selling products for this seller
        $topProducts = Product::where('seller_id', $sellerId)
          ->withCount(['orderDetails as total_sold' => function ($query) {
            $query->whereHas('order', function ($q) {
              $q->where('status', 'accepted'); // Ganti 'completed' dengan 'accepted'
            });
          }])
          ->orderBy('total_sold', 'desc')
          ->take(5)
          ->get();

        // Get recent reviews
        $recentReviews = Review::whereHas('product', function ($query) use ($sellerId) {
          $query->where('seller_id', $sellerId);
        })
          ->with(['user', 'product'])
          ->latest()
          ->take(5)
          ->get();

        // Calculate average rating
        $averageRating = Review::whereHas('product', function ($query) use ($sellerId) {
          $query->where('seller_id', $sellerId);
        })->avg('rating') ?? 0;

        // Ensure monthly orders data is not null
        $monthlyOrdersData = $monthlyOrdersData ?? collect();
        
    } catch (\Exception $e) {
        Log::error('Error in seller dashboard: ' . $e->getMessage());
        // Set default values in case of error
        $completeMonthlyData = collect(range(1, 12))->map(function ($month) {
            return [
                'month' => $month,
                'count' => 0,
                'revenue' => 0
            ];
        });
    }

    return view('dashboard.seller', compact(
      'totalProducts',
      'totalOrders',
      'totalRevenue',
      'monthlyRevenue',
      'recentOrders',
      'completeMonthlyData',
      'topProducts',
      'recentReviews',
      'averageRating'
    ));
  }
}
