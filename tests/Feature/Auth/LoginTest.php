<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_be_able_to_login()
    {
        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson(route('api.auth.login'), $payload);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'user',
                'access_token',
                'expires_at',
            ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => 1,
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);

        $this->assertInstanceOf(Carbon::class, Carbon::createFromTimestamp($response->json()['expires_at']));
    }

    #[DataProvider('invalidCredentialsDataProvider')]
    public function test_it_should_return_unauthorized_when_providing_wrong_credentials(array $credentials)
    {
        User::factory()->create([
            'email' => 'email@email.com',
            'password' => 'password',
        ]);

        $response = $this->postJson(route('api.auth.login'), $credentials);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    #[DataProvider('invalidPayloadDataProvider')]
    public function test_it_should_return_unprocessable_entity_when_providing_invalid_payload(array $credentials, array $rules)
    {
        $response = $this->postJson(route('api.auth.login'), $credentials);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function invalidPayloadDataProvider(): array
    {
        return [
            'missing_fields' => [
                [],
                ['missing_fields', 'email', 'password'],
            ],
            'email' => [
                ['email' => 'invalid_email', 'password' => 'password'],
                ['invalid_email', 'email'],
            ],
        ];
    }

    public static function invalidCredentialsDataProvider(): array
    {
        return [
            'wrong_email' => [
                ['email' => 'wrong_email@email.com', 'password' => 'password'],
            ],
            'wrong_password' => [
                ['email' => 'email@email.com', 'password' => 'wrong_password'],
            ],
        ];
    }
}
