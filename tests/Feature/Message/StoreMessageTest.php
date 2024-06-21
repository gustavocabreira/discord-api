<?php

namespace Tests\Feature\Message;

use App\Models\Channel;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class StoreMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_create_a_message(): void
    {
        $user = User::factory()->create();
        $guild = Guild::factory()->create(['owner_id' => $user->id]);
        $channel = Channel::factory()->create();
        $payload = [
            'content' => fake()->text(),
        ];

        $response = $this->actingAs($user)->postJson(route('api.guilds.channels.messages.store', [
            'guild' => $guild->id,
            'channel' => $channel->id,
        ]), $payload);

        $response->assertCreated();
        $response->assertJsonStructure([
            'id',
            'channel_id',
            'sender_id',
            'content',
            'created_at',
            'updated_at',
            'sender',
            'channel' => ['guild'],
        ]);
        $this->assertIsArray($response->json('sender'));
        $this->assertIsArray($response->json('channel'));
        $this->assertIsArray($response->json('channel.guild'));
        $this->assertEquals($payload['content'], $response->json('content'));
        $this->assertEquals($user->id, $response->json('sender_id'));
        $this->assertEquals($channel->id, $response->json('channel_id'));
        $this->assertEquals($response->json('sender.id'), $response->json('sender_id'));
        $this->assertEquals($response->json('channel.id'), $response->json('channel_id'));
        $this->assertDatabaseCount('messages', 1);
        $this->assertDatabaseHas('messages', [
            'content' => $payload['content'],
            'sender_id' => $user->id,
            'channel_id' => $channel->id,
        ]);
    }

    public function test_user_must_be_authenticated_to_create_a_message(): void
    {
        $user = User::factory()->create();
        $guild = Guild::factory()->create(['owner_id' => $user->id]);
        $channel = Channel::factory()->create();

        $payload = [
            'content' => fake()->text, 
        ];

        $response = $this->postJson(route('api.guilds.channels.messages.store', [
            'guild' => $guild->id,
            'channel' => $channel->id,
        ]), $payload);

        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['message'])
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }
}
