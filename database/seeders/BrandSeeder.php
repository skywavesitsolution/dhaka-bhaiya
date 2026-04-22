<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        DB::table('product_brands')->insert([
            ['name' => 'Brand A', 'slug' => 'brand-a', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Brand B', 'slug' => 'brand-b', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Brand C', 'slug' => 'brand-c', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
