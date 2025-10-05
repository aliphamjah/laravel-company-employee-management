<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@grtech.com')->first();
    }

    public function test_first_name_is_required(): void
    {
        $company = Company::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'last_name' => 'Doe',
                'company_id' => $company->id,
            ]);

        $response->assertSessionHasErrors('first_name');
    }

    public function test_last_name_is_required(): void
    {
        $company = Company::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'first_name' => 'John',
                'company_id' => $company->id,
            ]);

        $response->assertSessionHasErrors('last_name');
    }

    public function test_company_id_is_required(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'first_name' => 'John',
                'last_name' => 'Doe',
            ]);

        $response->assertSessionHasErrors('company_id');
    }

    public function test_company_id_must_exist(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'company_id' => 999999,
            ]);

        $response->assertSessionHasErrors('company_id');
    }

    public function test_email_must_be_valid(): void
    {
        $company = Company::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'company_id' => $company->id,
                'email' => 'invalid-email',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_valid_data_passes_validation(): void
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

        $response->assertSessionDoesntHaveErrors();
    }
}
