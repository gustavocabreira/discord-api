<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
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

        $response = $this->post(route('api.auth.register'), $payload);

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
}
