<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    #[DataProvider('credentialsDataProvider')]
    public function test_it_should_return_unauthorized_when_providing_wrong_credentials(array $credentials, array $rules)
    {
        $user = User::factory()->make()->makeVisible(['password'])->toArray();

        $user = User::create($user);

        $payload = [
            'email' => $user->email,
        ];

        if(isset($rules[0]) && !empty($credentials)) {
            $payload['email'] = $credentials['email'];
        }

        if(isset($credentials['password'])) {
            $payload['password'] = $credentials['password'];
        }

        $response = $this->postJson(route('api.auth.login'), $payload);

        if(in_array($rules[0], ['missing_fields', 'invalid_email'])) {
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        }
    }

    public static function credentialsDataProvider()
    {
        return [
            'missing_fields' => [
                [],
                ['missing_fields', 'email', 'password']
            ],
            'email' => [
                ['email' => 'invalid_email', 'password' => 'password'],
                ['invalid_email', 'email']
            ],
            'wrong_email' => [
                ['email' => 'wrong_email@email.com', 'password' => 'password'],
                ['wrong_email']
            ],
            'wrong_password' => [
                ['email' => 'john@example.com', 'password' => 'wrong_password'],
                ['password']
            ],
        ];
    }
}
