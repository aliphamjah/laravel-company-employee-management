<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@grtech.com')->first();
        $this->user = User::where('email', 'user@grtech.com')->first();
    }

    public function test_admin_can_view_employees_index(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('employees.index'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_employees(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('employees.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('employees.create'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_employee(): void
    {
        $company = Company::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'company_id' => $company->id,
                'email' => 'john@example.com',
                'phone' => '1234567890',
            ]);

        $response->assertRedirect(route('employees.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('employees', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'company_id' => $company->id,
        ]);
    }

    public function test_admin_can_view_edit_form(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('employees.edit', $employee));

        $response->assertStatus(200);
    }

    public function test_admin_can_update_employee(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('employees.update', $employee), [
                'first_name' => 'Updated',
                'last_name' => 'Name',
                'company_id' => $employee->company_id,
                'email' => 'updated@example.com',
            ]);

        $response->assertRedirect(route('employees.index'));
        
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'first_name' => 'Updated',
        ]);
    }

    public function test_admin_can_delete_employee(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('employees.destroy', $employee));

        $response->assertRedirect(route('employees.index'));
        
        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }

    public function test_deleting_company_cascades_to_employees(): void
    {
        $company = Company::factory()->create();
        $employee = Employee::factory()->create(['company_id' => $company->id]);

        $company->delete();

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }

    public function test_employees_can_be_searched(): void
    {
        Employee::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        Employee::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);
        Employee::factory()->create(['first_name' => 'Bob', 'last_name' => 'Johnson']);

        $response = $this->actingAs($this->admin)
            ->get(route('employees.index', ['search' => 'Jane']));

        $response->assertStatus(200);
    }

    public function test_employees_are_paginated(): void
    {
        Employee::factory()->count(15)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('employees.index'));

        $response->assertStatus(200);
    }

    public function test_employees_load_company_relationship(): void
    {
        $company = Company::factory()->create(['name' => 'Test Company']);
        Employee::factory()->create(['company_id' => $company->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('employees.index'));

        $response->assertStatus(200);
    }
}
