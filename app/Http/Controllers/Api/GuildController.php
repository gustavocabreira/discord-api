<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guild\GuildStoreRequest;
use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class GuildController extends Controller
{
    public function index(): JsonResponse
    {
        $guilds = Guild::query()->latest()->get();

        return response()->json($guilds, Response::HTTP_OK);
    }

    public function store(GuildStoreRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $guild = Guild::query()->create($payload);

        return response()->json($guild, Response::HTTP_CREATED);
    }

    public function destroy(Guild $guild): JsonResponse
    {
        Gate::authorize('delete', $guild);

        $guild->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
