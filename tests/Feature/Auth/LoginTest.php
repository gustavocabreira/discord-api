<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
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
}
