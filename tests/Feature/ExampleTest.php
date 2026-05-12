<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_open_dashboard(): void
    {
        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@example.test',
            'password' => 'password123',
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertStatus(200);
    }

    public function test_register_redirects_to_login_without_auto_login(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Admin Baru',
            'email' => 'admin-baru@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'admin-baru@example.test',
        ]);

        $this->assertGuest();
    }
}
