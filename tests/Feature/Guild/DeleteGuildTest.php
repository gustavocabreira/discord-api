<?php

namespace Tests\Feature\Guild;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteGuildTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_be_able_to_delete_a_guild(): void
    {
        $user = User::factory()->create();
        $guild = Guild::factory()->create([
            'owner_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->deleteJson(route('api.guilds.destroy', ['guild' => $guild->id]));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('guilds', [
            'id' => $guild->id,
        ]);
    }

    public function test_only_owner_can_delete_a_guild(): void
    {
        $owner = User::factory()->create();
        $guild = Guild::factory()->create([
            'owner_id' => $owner->id,
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('api.guilds.destroy', ['guild' => $guild->id]));

        $response
            ->assertNotFound()
            ->assertJsonStructure(['message']);
    }
}
