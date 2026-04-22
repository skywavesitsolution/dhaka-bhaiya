<?php

namespace Tests\Feature;

use App\Models\Party;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PartyControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testAddParty(): void
    {
        $user = User::factory()->create();

        $openingBalance = fake()->randomNumber(3);
        $payload = [
            'name' => fake()->name,
            'type' => fake()->randomElements(['Marka', 'Driver', 'Customer'])[0],
            'openingBalance' => $openingBalance,
            'email' => fake()->email(),
            'company_name' => fake()->company(),
            'address' => fake()->address(),
        ];

        $response = $this->actingAs($user)->post('/add-party', $payload);

        $this->assertDatabaseHas('parties', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'type' => $payload['type']
        ]);
    }


    public function testUpdateParty(): void
    {
        $user = User::factory()->create();
        $party = Party::factory()->create();

        $payload = [
            'name' => fake()->name,
            'email' => fake()->email(),
            'company_name' => fake()->company(),
            'address' => fake()->address(),
        ];

        $this->actingAs($user)->post("/update-party/{$party->id}", $payload);

        $this->assertDatabaseHas('parties', [
            'id' => $party->id,
            'name' => $payload['name'],
            'email' => $payload['email'],
            'company_name' => $payload['company_name'],
            'address' => $payload['address']
        ]);
    }
}
