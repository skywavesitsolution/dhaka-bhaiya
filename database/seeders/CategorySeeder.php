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
            ['id' => 1, 'name' => 'Special', 'slug' => 'special', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Pizza Treat', 'slug' => 'pizza-treat', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Crispy Chicken', 'slug' => 'crispy-chicken', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Pasta', 'slug' => 'pasta', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Paratha Roll & Wrap', 'slug' => 'paratha-roll-wrap', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Sandwich', 'slug' => 'sandwich', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Starter', 'slug' => 'starter', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Burger', 'slug' => 'burger', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
