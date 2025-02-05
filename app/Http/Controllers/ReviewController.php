<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $order = Order::with(['details.product', 'details.reviews'])->findOrFail($request->order_id);

        // Cek apakah user sudah memberikan review
        $hasReviewed = Review::where('order_id', $order->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($hasReviewed) {
            return redirect()->route('orders.history')
                ->with('error', 'Anda sudah memberikan review untuk pesanan ini.');
        }

        // Pastikan hanya user yang memesan produk ini dapat memberikan review
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan order memiliki details
        if ($order->details->isEmpty()) {
            return redirect()->route('orders.history')
                ->with('error', 'Tidak dapat memberikan review: Detail pesanan tidak ditemukan.');
        }

        return view('reviews.create', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('review_images', 's3');
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'review' => $request->review,
            'rating' => $request->rating,
            'image' => $imagePath,
        ]);

        return redirect()->route('orders.history')->with('success', 'Review berhasil ditambahkan.');
    }

    public function show($order_id)
    {
        $review = Review::where('order_id', $order_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$review) {
            return redirect()->route('orders.history')
                ->with('error', 'Review tidak ditemukan.');
        }

        $order = Order::with('details.product')->findOrFail($order_id);

        // Pastikan hanya user yang memiliki pesanan ini dapat melihat review
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('reviews.show', compact('review', 'order'));
    }
}
