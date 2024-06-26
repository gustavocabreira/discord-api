<?php

namespace Tests\Feature\Invite;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateInviteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_should_be_able_to_generate_an_invite_to_a_guild(): void
    {
        $user = User::factory()->create();
        $guild = Guild::factory()->create([
            'owner_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson(route('api.guilds.invites.store', [
            'guild' => $guild->id,
        ]));

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('invites', ['guild_id' => $guild->id]);
        $this->assertDatabaseCount('invites', 1);
    }
}
