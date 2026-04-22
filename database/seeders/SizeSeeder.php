<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        DB::table('product_sizes')->insert([
            ['id' => 1, 'name' => 'Extra Small', 'slug' => 'extra-small', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Small', 'slug' => 'small', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Medium', 'slug' => 'extra-medium', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Large', 'slug' => 'large', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Extra Large', 'slug' => 'extra-large', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
