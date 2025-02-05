<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
  public function search(Request $request)
  {
    $query = Product::query()->with('category', 'images');

    // Search by keyword
    if ($request->has('query')) {
      $searchTerm = $request->input('query'); // Updated line
      $query->where(function ($q) use ($searchTerm) {
        $q->where('name', 'like', "%{$searchTerm}%")
          ->orWhere('description', 'like', "%{$searchTerm}%");
      });
    }

    // Filter by category
    if ($request->has('category')) {
      $query->where('category_id', $request->input('category'));
    }

    $products = $query->paginate(12);

    return view('products.index', compact('products'));
  }
}
