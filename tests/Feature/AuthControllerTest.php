<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $password = 'password';
    protected string $name = 'John Doe';
    protected string $email = 'john2@example.com';

    /**
     * Teste de login com credenciais válidas.
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $payload = [
            'email' => $this->email,
            'password' => $this->password
        ];

        $response = $this->postJson(route('api.login'), $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'expiresIn']);
    }

    /**
     * Teste de login com credenciais inválidas.
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make($this->password),
        ]);

        $payload = [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson(route('api.login'), $payload);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'Invalid credentials']);
    }

    /**
     * Teste de tentativa de acesso ao perfil sem token.
     */
    public function test_unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->postJson(route('api.logout'));

        $response->assertStatus(401);
    }

    /**
     * Teste de logout com token válido.
     */
    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                         ->postJson(route('api.logout'));

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Successfully logged out']);
    }
}
