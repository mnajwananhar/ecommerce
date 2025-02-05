<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('categories')->insert([
      ['name' => 'Fashion & Accessories'],
      ['name' => 'Electronics & Gadgets'],
      ['name' => 'Home & Furniture'],
      ['name' => 'Beauty & Personal Care'],
      ['name' => 'Health & Wellness'],
      ['name' => 'Sports & Outdoors'],
      ['name' => 'Automotive & Motorcycle'],
      ['name' => 'Books & Stationery'],
      ['name' => 'Toys & Hobbies'],
      ['name' => 'Food & Beverages'],
      ['name' => 'Baby & Kids'],
      ['name' => 'Pet Supplies'],
      ['name' => 'Jewelry & Watches'],
      ['name' => 'Handmade & Craft'],
      ['name' => 'Office & Business Supplies'],
    ]);
  }
}
