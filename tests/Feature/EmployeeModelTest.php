<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_an_employee(): void
    {
        $company = Company::factory()->create();
        
        $employee = Employee::create([
            'first_name' => 'Alip',
            'last_name' => 'Hamjah',
            'company_id' => $company->id,
            'email' => 'alip@example.com',
            'phone' => '1234567890',
        ]);

        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertEquals('Alip', $employee->first_name);
        $this->assertDatabaseHas('employees', [
            'first_name' => 'Alip',
            'last_name' => 'Hamjah',
        ]);
    }

    public function test_it_belongs_to_a_company(): void
    {
        $company = Company::factory()->create();
        $employee = Employee::factory()->create([
            'company_id' => $company->id
        ]);

        $this->assertInstanceOf(Company::class, $employee->company);
        $this->assertEquals($company->id, $employee->company->id);
    }

    public function test_first_name_and_last_name_are_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        $company = Company::factory()->create();
        
        Employee::create([
            'company_id' => $company->id,
        ]);
    }

    public function test_company_id_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Employee::create([
            'first_name' => 'Alip',
            'last_name' => 'Hamjah',
        ]);
    }

    public function test_company_has_many_employees(): void
    {
        $company = Company::factory()->create();
        
        Employee::factory()->count(3)->create([
            'company_id' => $company->id
        ]);

        $this->assertCount(3, $company->employees);
        $this->assertInstanceOf(Employee::class, $company->employees->first());
    }
}
