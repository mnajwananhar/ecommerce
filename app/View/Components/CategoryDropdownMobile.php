<?php

namespace App\View\Components;

use App\Models\Category;
use Illuminate\View\Component;

class CategoryDropdownMobile extends Component
{
  public $categories;

  public function __construct()
  {
    $this->categories = Category::all();
  }

  public function render()
  {
    return view('components.category-dropdown-mobile');
  }
}
