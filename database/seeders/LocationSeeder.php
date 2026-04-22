<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        DB::table('product_locations')->insert([
            ['id' => 1, 'name' => 'General', 'slug' => 'general', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Ground Floor', 'slug' => 'ground-floor', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Basement', 'slug' => 'basement', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Floor 1', 'slug' => 'floor-1', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Floor 2', 'slug' => 'floor-2', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Kitchen', 'slug' => 'kitchen', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
