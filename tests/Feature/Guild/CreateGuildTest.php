<?php

namespace Tests\Feature\Guild;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
