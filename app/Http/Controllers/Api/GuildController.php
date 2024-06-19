<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class GuildController extends Controller
{
    public function index(): JsonResponse
    {
        $guilds = Guild::query()->latest()->get();

        return response()->json($guilds, Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $guild = Guild::query()->create([
            'name' => $request->input('name'),
            'owner_id' => request()->user()->id,
        ]);

        return response()->json($guild, Response::HTTP_CREATED);
    }

    public function destroy(Guild $guild): JsonResponse
    {
        Gate::authorize('delete', $guild);

        $guild->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
