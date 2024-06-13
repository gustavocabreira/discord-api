<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_return_logged_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('api.user.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'name',
                'email'
            ]);

        $response->assertSimilarJson($user->toArray());
    }

    public function test_it_should_return_unauthorized_when_providing_invalid_token()
    {
        $response = $this->getJson(route('api.user.index'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
