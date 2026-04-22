<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class MeasuringUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        DB::table('measuring_units')->insert([
            [
                'id' => 1,
                'name' => 'Pieces',
                'symbol' => 'pcs',
                'quantity' => 1,
                'description' => 'Pieces',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'quantity' => 1,
                'description' => 'Used for measuring weight',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Litre',
                'symbol' => 'L',
                'quantity' => 1,
                'description' => 'Used for measuring volume',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Dozen',
                'symbol' => 'doz',
                'quantity' => 12,
                'description' => '12 in one dozen',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
