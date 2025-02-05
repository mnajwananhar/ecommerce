<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Hanya tampilkan produk dengan stok > 0
        $query = Product::query()->with('category', 'images')->where('stock', '>', 0);

        // Tetap pertahankan filter dan pencarian yang ada
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Tetap pertahankan filter kategori
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Tetap pertahankan filter harga
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tetap pertahankan sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Tetap pertahankan paginasi
        $categories = Category::all();
        $products = $query->paginate(12);

        return view('products.index', compact('products', 'categories'));
    }

    public function listproduct()
    {
        $categories = Category::all();
        $activeProducts = collect();
        $trashedProducts = collect();

        if (Auth::user()->role === 'seller') {
            $activeProducts = Product::where('seller_id', Auth::id())
                ->with('category', 'images')
                ->paginate(10);

            $trashedProducts = Product::where('seller_id', Auth::id())
                ->onlyTrashed()
                ->with('category', 'images')
                ->paginate(10);
        }

        return view('products.manage.index', compact('activeProducts', 'trashedProducts', 'categories'));
    }

    // public function destroy(Product $manage)
    // {
    //     if ($manage->seller_id !== auth()->id()) {
    //         abort(403, 'Unauthorized');
    //     }

    //     $manage->delete();
    //     return redirect()->route('products.manage.index')->with('status', 'Produk berhasil dihapus sementara.');
    // }


    public function edit($id)
    {
        $manage = Product::findOrFail($id);
        if ($manage->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $categories = Category::all();
        return view('products.manage.edit', compact('manage', 'categories'));
    }

    public function destroy($id)
    {
        $manage = Product::findOrFail($id);
        if ($manage->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $manage->delete();
        return redirect()->route('products.manage.index')->with('status', 'Produk berhasil dihapus sementara.');
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $product->restore();
        return redirect()->route('products.manage.index')->with('status', 'Produk berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Delete associated images
        foreach ($product->images as $image) {
            Storage::disk('s3')->delete($image->image_path);
            $image->delete();
        }

        $product->forceDelete();
        return redirect()->route('products.manage.index')->with('status', 'Produk berhasil dihapus permanen.');
    }



    public function update(Request $request, $id)
    {
        $manage = Product::findOrFail($id);
        if ($manage->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $manage->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'weight' => $request->weight,
            'stock' => $request->stock,
        ]);

        // Handle gambar yang akan dihapus
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id === $manage->id) {
                    Storage::disk('s3')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Handle upload gambar baru
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product-images', 's3');
                ProductImage::create([
                    'product_id' => $manage->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('products.manage.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.manage.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0', // Tambahkan validasi stock
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'weight' => $request->weight,
            'stock' => $request->stock, // Tambahkan stock
            'category_id' => $request->category_id,
            'seller_id' => Auth::id(),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product-images', 's3');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('products.manage.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $product = Product::with('category', 'images')->findOrFail($id);
        return view('products.show', compact('product'));
    }
    public function show2($id)
    {
        $product = Product::with('category', 'images')->findOrFail($id);
        return view('products.manage.show', compact('product'));
    }


    public function welcome(Request $request)
    {
        // Hanya tampilkan produk dengan stok > 0
        $query = Product::query()->with('category', 'images')->where('stock', '>', 0);

        // Tetap pertahankan filter dan pencarian yang ada
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Tetap pertahankan filter kategori
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Tetap pertahankan filter harga
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tetap pertahankan sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Tetap pertahankan paginasi
        $categories = Category::all();
        $products = $query->paginate(12);

        return view('welcome', compact('products', 'categories'));
    }
}
