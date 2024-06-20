<?php

namespace Tests\Feature\Guild;

use App\Models\Channel;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowGuildTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_be_able_to_retrieve_a_guild(): void
    {
        $user = User::factory()->create();
        $guild = Guild::factory()->create(['owner_id' => $user->id]);
        Channel::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson(route('api.guilds.show', $guild->id));

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'name',
            'owner_id',
            'created_at',
            'updated_at',
            'channels',
        ]);
        $this->assertIsArray($response->json('channels'));
        $this->assertCount(3, $response->json('channels'));
    }
}
