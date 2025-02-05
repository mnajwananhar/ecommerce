<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;

class CategoryDropdownContent extends Component
{
  public $categories;

  public function __construct()
  {
    $this->categories = Category::all();
  }

  public function render()
  {
    return view('components.category-dropdown-content');
  }
}
