<?php

namespace Database\Seeders\Product;

use App\Models\User;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $categories = DB::table('product_categories')->get();
        $brands = DB::table('product_brands')->get();
        $measuring_units = DB::table('measuring_units')->get();
        $sizes = DB::table('product_sizes')->get();

        
    }
}
