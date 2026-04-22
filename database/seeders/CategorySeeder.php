<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        DB::table('product_categories')->insert([
            ['name' => 'Category A', 'slug' => 'category-a', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Category B', 'slug' => 'category-b', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Category C', 'slug' => 'category-c', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
