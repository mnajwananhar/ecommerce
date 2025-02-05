<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;


class CategoryController extends Controller
{
  public function index()
  {
    $categories = Category::withCount('products')->paginate(10);
    return view('categories.index', compact('categories')); // Pastikan langsung dikirim ke view
  }


  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
    ]);

    Category::create($request->all());
    return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan');
  }

  public function update(Request $request, Category $category)
  {
    $request->validate([
      'name' => 'required|string|max:255',
    ]);

    $category->update($request->all());
    return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui');
  }

  public function destroy(Category $category)
  {
    // Find or create the '-' category
    $defaultCategory = Category::firstOrCreate(['name' => '-']);

    // Move all products to the '-' category
    $category->products()->update(['category_id' => $defaultCategory->id]);

    $category->delete();
    return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
  }
}
