<?php

namespace Tests\Feature\Guild;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateGuildTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_should_be_able_to_create_a_guild(): void
    {
        $user = User::factory()->create();
        $payload = [
            'name' => fake()->name,
        ];

        $response = $this->actingAs($user)->postJson(route('api.guilds.store'), $payload);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['id', 'name']);

        $this->assertDatabaseHas('guilds', $payload);
    }

    public function test_user_must_be_authenticated_to_create_a_guild(): void
    {
        $payload = [
            'name' => fake()->name,
        ];

        $response = $this->postJson(route('api.guilds.store'), $payload);

        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['message'])
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    public function test_it_should_return_unprocessable_entity_when_trying_to_create_a_guild_with_invalid_payload(): void
    {
        $user = User::factory()->create();
        $invalidPayload = [
            'name' => null,
        ];

        $response = $this->actingAs($user)->postJson(route('api.guilds.store'), $invalidPayload);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }
}
