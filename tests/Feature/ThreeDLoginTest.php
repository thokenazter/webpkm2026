<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreeDLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_displays_3d_character_elements()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('character-container', false);
        $response->assertSee('character-head', false);
        $response->assertSee('character-eyes', false);
        $response->assertSee('character-mouth', false);
        $response->assertSee('floating-shapes', false);
    }

    /** @test */
    public function login_page_includes_3d_assets()
    {
        $response = $this->get('/login');

        $response->assertSee('3d-login.css');
        $response->assertSee('3d-login.js');
        $response->assertSee('3d-login-dark.css');
    }

    /** @test */
    public function login_form_maintains_laravel_structure()
    {
        $response = $this->get('/login');

        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="remember"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee('method="POST"', false);
    }

    /** @test */
    public function successful_login_works_with_3d_interface()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function failed_login_shows_validation_errors()
    {
        $response = $this->post('/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $response->assertSee('hasValidationErrors = true', false);
    }

    /** @test */
    public function login_page_has_proper_accessibility_attributes()
    {
        $response = $this->get('/login');

        $response->assertSee('role="img"', false);
        $response->assertSee('aria-label', false);
        $response->assertSee('aria-describedby', false);
        $response->assertSee('aria-live="polite"', false);
    }

    /** @test */
    public function remember_me_functionality_works()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
            'remember' => 'on',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        
        // Check if remember token is set
        $this->assertNotNull($user->fresh()->remember_token);
    }

    /** @test */
    public function csrf_protection_is_maintained()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Without CSRF token, should get 419 status
        $response->assertStatus(419);
    }

    /** @test */
    public function forgot_password_link_is_present()
    {
        $response = $this->get('/login');

        $response->assertSee('Forgot your password?');
        $response->assertSee(route('password.request'));
    }
}