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

    public function test_login_page_shows_google_unavailable_notice_without_configuration(): void
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee('Login Google belum aktif')
            ->assertDontSee(route('google.redirect'), false);
    }

    public function test_login_page_has_google_login_option_when_configured(): void
    {
        config([
            'services.google.client_id' => 'google-client-id',
            'services.google.client_secret' => 'google-client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);

        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee('Masuk dengan Google')
            ->assertSee(route('google.redirect'), false);
    }

    public function test_google_login_without_configuration_redirects_with_notice(): void
    {
        $this->get(route('google.redirect'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('google_unavailable');
    }
}
