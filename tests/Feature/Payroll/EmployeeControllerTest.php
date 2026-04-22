<?php

namespace Tests\Feature\Payroll;

use App\Models\Payroll\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testAddEmployee(): void
    {
        $user = User::factory()->create();

        $payload = [
            'name' => fake()->name(),
            'opening_balance' => fake()->numberBetween(10000, 50000),
            'opening_advance_balnce' => fake()->numberBetween(0, 10000),
            'basic_salary' => fake()->numberBetween(10000, 50000),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'joining_date' => fake()->date(),
            'address' => fake()->address(),
        ];

        $this->actingAs($user)->post('/employee-create', $payload);

        $this->assertDatabaseHas('employees', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'opening_balance' => $payload['opening_balance']
        ]);
    }

    public function testUpdateEmployee(): void
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create();

        $payload = [
            'name' => fake()->name(),
            'basic_salary' => fake()->numberBetween(10000, 50000),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'joining_date' => fake()->date(),
            'address' => fake()->address(),
        ];

        $this->actingAs($user)->post('/employee-update/' . $employee->id, $payload);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => $payload['name'],
            'email' => $payload['email'],
            'basic_salary' => $payload['basic_salary'],
            'phone' => $payload['phone'],
            'joining_date' => $payload['joining_date'],
            'address' => $payload['address'],
        ]);
    }
}
