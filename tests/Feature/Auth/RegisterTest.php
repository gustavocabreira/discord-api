<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_be_able_to_register_a_new_user(): void
    {
        $payload = [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('api.auth.register'), $payload);

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => $payload['name'],
            'email' => $payload['email'],
        ]);
    }

   #[DataProvider('userDataProvider')]
    public function test_user_registration_validation($userData, $rule)
    {
        $response = $this->postJson(route('api.auth.register'), $userData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors($rule);
    }

    public function test_email_should_be_unique()
    {
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $payload = [
            'name' => fake()->name,
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('api.auth.register'), $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['email']);
    }

    public static function userDataProvider()
    {
        return [
            'missing_fields' => [
                [],
                ['name', 'email', 'password']
            ],
            'invalid_email' => [
                ['name' => 'John Doe', 'email' => 'invalid_email', 'password' => 'password', 'password_confirmation' => 'password'],
                ['email']
            ],
            'short_password' => [
                ['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'short', 'password_confirmation' => 'short'],
                ['password']
            ],
            'password_confirmation_mismatch' => [
                ['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'password', 'password_confirmation' => 'not_matching'],
                ['password']
            ],
        ];
    }
}
