<?php

namespace Tests\Feature\Guild;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ListGuildTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_be_able_to_return_all_guilds(): void
    {
        $requestUser = User::factory()->createMany(5)->first();
        Guild::factory()->count(3)->create([
            'owner_id' => rand(1,5),
        ]);

        $response = $this->actingAs($requestUser)->get(route('api.guilds.index'));

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'logo_path',
                    'name',
                    'owner_id',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }
}
