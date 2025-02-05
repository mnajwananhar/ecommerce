<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use function Pest\Laravel\call;

use Illuminate\Database\Seeder;
use Database\Seeders\CategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(SellerSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(CategorySeeder::class);
    }
}
