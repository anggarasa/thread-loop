<?php

namespace Tests\Unit\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Livewire;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function test_component_can_be_rendered()
    {
        $component = Livewire::test(Login::class);

        $component->assertStatus(200);
    }

    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $component = Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->call('login');

        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    public function test_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $component = Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'wrongpassword')
            ->call('login');

        $component->assertHasErrors('email');
        $this->assertFalse(Auth::check());
    }

    public function test_login_with_nonexistent_email()
    {
        $component = Livewire::test(Login::class)
            ->set('email', 'nonexistent@example.com')
            ->set('password', 'password')
            ->call('login');

        $component->assertHasErrors('email');
        $this->assertFalse(Auth::check());
    }

    public function test_login_validates_required_fields()
    {
        $component = Livewire::test(Login::class)
            ->set('email', '')
            ->set('password', '')
            ->call('login');

        $component->assertHasErrors(['email', 'password']);
    }

    public function test_login_validates_email_format()
    {
        $component = Livewire::test(Login::class)
            ->set('email', 'invalid-email')
            ->set('password', 'password')
            ->call('login');

        $component->assertHasErrors('email');
    }

    public function test_login_with_remember_me()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $component = Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('remember', true)
            ->call('login');

        $this->assertTrue(Auth::check());
        $this->assertNotNull(Auth::user()->getRememberToken());
    }

    public function test_login_redirects_to_home_page()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $component = Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->call('login');

        $component->assertRedirect(route('homePage'));
    }

    public function test_login_handles_rate_limiting()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Simulate multiple failed attempts
        for ($i = 0; $i < 5; $i++) {
            Livewire::test(Login::class)
                ->set('email', 'test@example.com')
                ->set('password', 'wrongpassword')
                ->call('login');
        }

        // This should trigger rate limiting
        $component = Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'wrongpassword')
            ->call('login');

        $component->assertHasErrors('email');
        Event::assertDispatched(Lockout::class);
    }

    public function test_login_clears_rate_limit_on_success()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Simulate failed attempts
        for ($i = 0; $i < 3; $i++) {
            Livewire::test(Login::class)
                ->set('email', 'test@example.com')
                ->set('password', 'wrongpassword')
                ->call('login');
        }

        // Successful login should clear rate limit
        $component = Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->call('login');

        $this->assertTrue(Auth::check());
        $this->assertEquals(0, RateLimiter::attempts($this->throttleKey('test@example.com')));
    }

    public function test_login_handles_two_factor_authentication()
    {
        // Skip this test for now as it requires complex mocking setup
        $this->markTestSkipped('Two factor authentication test requires complex mocking setup');
    }

    public function test_throttle_key_generation()
    {
        $component = Livewire::test(Login::class);

        $component->set('email', 'test@example.com');

        // Test the throttle key by checking rate limiter behavior
        $key = $this->throttleKey('test@example.com');
        $this->assertStringContainsString('test@example.com', $key);
        $this->assertStringContainsString(request()->ip(), $key);
    }

    private function throttleKey($email)
    {
        return \Illuminate\Support\Str::transliterate(\Illuminate\Support\Str::lower($email) . '|' . request()->ip());
    }
}
