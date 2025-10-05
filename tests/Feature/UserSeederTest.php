<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_admin_user(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@grtech.com',
        ]);

        $admin = User::where('email', 'admin@grtech.com')->first();
        $this->assertNotNull($admin);
        $this->assertEquals('Administrator', $admin->name);
    }

    public function test_database_seeder_creates_regular_user(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => 'user@grtech.com',
        ]);

        $user = User::where('email', 'user@grtech.com')->first();
        $this->assertNotNull($user);
    }
}