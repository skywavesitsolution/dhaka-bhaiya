<?php

namespace Tests\Feature\Payroll;

use App\Models\Payroll\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdvanceIssueControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdvanceIssueToEmployee(): void
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create();

        $returnType = fake()->randomElements(['Cash', 'SalaryDeduction'])[0];
        if ($returnType == 'Cash') {
            $noOfInstallments = 1;
        } else {
            $noOfInstallments = fake()->numberBetween(1, 5);
        }

        $payload = [
            'employeeId' => $employee->id,
            'advanceAmount' => fake()->numberBetween(10000, 40000),
            'returnType' => $returnType,
            'noOfInstallment' => $noOfInstallments,
            'return_date' => fake()->date(),
            'payemnt_from_account' => fake()->randomNumber(2),
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
