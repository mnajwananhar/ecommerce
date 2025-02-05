<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        // Inisialisasi konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function checkout()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $totalWeight = $cartItems->sum(function ($item) {
            return $item->product->weight * $item->quantity;
        });

        $origin = '501';

        return view('orders.checkout', compact('cartItems', 'totalWeight', 'origin'));
    }

    public function calculateShipping(Request $request)
    {
        try {
            $validated = $request->validate([
                'origin' => 'required|string',
                'destination' => 'required|string',
                'weight' => 'required|integer',
                'courier' => 'required|string',
            ]);

            $client = new Client();
            $response = $client->post(config('services.rajaongkir.base_url') . '/calculate/domestic-cost', [
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/x-www-form-urlencoded',
                    'key' => config('services.rajaongkir.api_key'),
                ],
                'form_params' => $validated,
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json([
                'success' => true,
                'data' => $data['data'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error calculating shipping:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'cart' => 'required|array',
                'shipping_address' => 'required|string',
                'courier' => 'required|string',
                'shipping_cost' => 'required|numeric',
            ]);

            $cartItems = $request->input('cart');

            if (empty($cartItems)) {
                throw new \Exception('Keranjang kosong.');
            }

            $userId = Auth::id();
            $totalPrice = 0;

            // Hitung total harga
            foreach ($cartItems as $item) {
                $product = Product::findOrFail($item['product_id']);
                $totalPrice += $product->price * $item['quantity'];
            }

            $totalPrice += $request->shipping_cost;
            $totalPrice = (int) $totalPrice;

            // Buat order baru
            $order = Order::create([
                'user_id' => $userId,
                'shipping_address' => $request->shipping_address,
                'courier' => $request->courier,
                'shipping_cost' => $request->shipping_cost,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // Simpan detail order untuk setiap item
            foreach ($cartItems as $item) {
                $product = Product::findOrFail($item['product_id']);
                $order->details()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ]);
            }

            // Generate Snap Token
            $snapToken = $this->getSnapToken($order);

            // Hapus item dari keranjang setelah order berhasil dibuat
            CartItem::where('user_id', $userId)->delete();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error in store order:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getSnapToken($order)
    {
        try {
            $items = $order->details->map(function ($detail) {
                return [
                    'id' => $detail->product_id,
                    'price' => (int) $detail->price,
                    'quantity' => $detail->quantity,
                    'name' => $detail->product->name,
                ];
            })->toArray();

            // Tambahkan biaya pengiriman sebagai item
            $items[] = [
                'id' => 'shipping_cost',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman (' . $order->courier . ')',
            ];

            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $order->id . '-' . time(),
                    'gross_amount' => (int) $order->total_price,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'shipping_address' => [
                        'address' => $order->shipping_address,
                    ]
                ],
                'item_details' => $items,
            ];

            Log::info('Generating snap token with params:', $params);

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            Log::info('Snap token generated:', ['token' => $snapToken]);

            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Error generating snap token:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('details.product')
            ->orderBy('created_at', 'desc')
            ->paginate(5); // Add pagination with 5 items per page
        return view('orders.history', compact('orders'));
    }

    public function details($id)
    {
        $order = Order::with([
            'details.product.images',
            'details.product.seller',
            'user'
        ])->findOrFail($id);

        return view('orders.details', compact('order'));
    }

    public function manage()
    {
        $orders = Order::with('details.product')
            ->orderBy('created_at', 'desc')
            ->paginate(5); // Add pagination with 5 items per page
        return view('orders.manage', compact('orders'));
    }

    public function accept($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->status = 'accepted';
            $order->save();

            return redirect()->route('orders.manage')->with('success', 'Pesanan berhasil disetujui.');
        } catch (\Exception $e) {
            Log::error('Error accepting order:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('orders.manage')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->status = 'rejected';
            $order->save();

            return redirect()->route('orders.manage')->with('success', 'Pesanan berhasil ditolak.');
        } catch (\Exception $e) {
            Log::error('Error rejecting order:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('orders.manage')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {


        // Debug


        return view('orders.details', compact('order'));
    }
}
