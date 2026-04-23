<?php

namespace Database\Seeders\Party;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $user = User::first();
        DB::table('parties')->insert([
            ['id' => 1, 'name' => 'Pizza House', 'type' => 'supplier', 'opening_balance' => 0.00, 'balance' => 0.00, 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
