<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang
     */
    public function index()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        // Hitung total harga
        $cartTotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'cartTotal'));
    }


    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403); // Unauthorized
        }

        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Menambahkan item ke keranjang
     */
    public function add(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($productId);

        // Check stock availability (includes existing cart quantity)
        if (!$this->checkStockAvailability($productId, $request->quantity)) {
            return redirect()->back()
                ->with('error', 'Stok produk tidak mencukupi untuk jumlah yang diminta.');
        }

        // Check if the cart item already exists and increment if so.
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $request->quantity
            ]);
        }

        // Decrease product stock by added quantity.
        $product->stock -= $request->quantity;
        $product->save();

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    private function checkStockAvailability($productId, $quantity)
    {
        $product = Product::findOrFail($productId);

        // Check if requested quantity is available
        if ($product->stock < $quantity) {
            return false;
        }

        // Get total quantity in existing cart for this product
        $existingCartQuantity = CartItem::where('product_id', $productId)
            ->where('user_id', Auth::id())
            ->sum('quantity');

        // Check if total quantity (existing + new) exceeds available stock
        return ($existingCartQuantity + $quantity) <= $product->stock;
    }




    /**
     * Checkout (opsional, jika ingin digunakan dalam keranjang)
     */
    public function checkout(Request $request)
    {
        $selectedIds = $request->input('cartItems');
        if (!$selectedIds || count($selectedIds) === 0) {
            return redirect()->route('cart.index')->with('error', 'Pilih item terlebih dahulu untuk checkout.');
        }

        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id', $selectedIds)
            ->get();

        $cartTotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.checkout', compact('cartItems', 'cartTotal'));
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Return JSON response untuk AJAX request
        return response()->json([
            'success' => true,
            'newQuantity' => $cartItem->quantity,
            'subtotal' => $cartItem->quantity * $cartItem->product->price,
            'cartTotal' => CartItem::where('user_id', Auth::id())->get()->sum(function ($item) {
                return $item->product->price * $item->quantity;
            })
        ]);
    }
}
