<?php

namespace Tests\Feature\Channel;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_be_able_to_create_a_new_channel(): void
    {
        $user = User::factory()->create();
        $guild = Guild::factory()->create([
            'owner_id' => $user->id,
        ]);

        $payload = [
            'name' => fake()->name,
        ];

        $response = $this->actingAs($user)->postJson(route('api.guilds.channels.store', [
            'guild' => $guild->id,
        ]), $payload);

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['id', 'guild_id', 'name']);

        $this
            ->assertDatabaseHas('channels', [
                'guild_id' => $guild->id,
                'name' => $payload['name'],
            ])
            ->assertDatabaseCount('channels', 1);
    }

    public function test_only_guild_owner_can_create_a_channel(): void
    {
        $guildOwner = User::factory()->create();
        $guild = Guild::factory()->create([
            'owner_id' => $guildOwner->id,
        ]);

        $user = User::factory()->create();

        $payload = [
            'name' => fake()->name,
        ];

        $response = $this->actingAs($user)->postJson(route('api.guilds.channels.store', [
            'guild' => $guild->id,
        ]), $payload);

        $response
            ->assertNotFound()
            ->assertJsonStructure(['message']);
    }
}
