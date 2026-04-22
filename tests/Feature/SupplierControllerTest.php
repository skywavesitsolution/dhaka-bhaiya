<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testAddSupplier(): void
    {
        $user = User::factory()->create();

        $payload = [
            'name' => fake()->name,
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'company_name' => fake()->company(),
            'address' => fake()->address(),
        ];

        $response = $this->actingAs($user)->post('/add-supplier', $payload);

        $this->assertDatabaseHas('suppliers', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'company_name' => $payload['company_name']
        ]);
    }
}
