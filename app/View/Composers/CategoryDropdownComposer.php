<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryDropdownComposer
{
  public function compose(View $view)
  {
    $categories = Category::withCount('products')->get();
    $view->with('categories', $categories);
  }
}
