<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Company;

class CompanyModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_company(): void
    {
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test@company.com',
            'website' => 'https://testcompany.com',
        ]);

        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals('Test Company', $company->name);
        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company',
            'email' => 'test@company.com',
        ]);
    }

    public function test_it_has_fillable_attributes(): void
    {
        $company = new Company();
        
        $fillable = ['name', 'email', 'logo', 'website'];
        
        $this->assertEquals($fillable, $company->getFillable());
    }

    public function test_name_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Company::create([
            'email' => 'test@company.com',
        ]);
    }
}
