<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User; 

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Seed admin and user
    }
    
    public function test_admin_user_can_access_protected_routes(): void
    {
        $admin = User::where('email', 'admin@grtech.com')->first();

        $response = $this->actingAs($admin)->get('/admin/test');

        $response->assertStatus(200);
    }

    public function test_non_admin_user_cannot_access_protected_routes(): void
    {
        $user = User::where('email', 'user@grtech.com')->first();

        $response = $this->actingAs($user)->get('/admin/test');

        $response->assertStatus(403);
    }

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get('/admin/test');

        $response->assertRedirect('/login');
    }
}
