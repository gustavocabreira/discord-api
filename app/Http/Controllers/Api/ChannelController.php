<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ChannelController extends Controller
{
    public function store(Guild $guild, Request $request): JsonResponse
    {
        Gate::authorize('createChannel', $guild);

        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $channel = $guild->channels()->create($request->only('name'));
        $channel = $channel->load('guild');

        return response()->json($channel, Response::HTTP_CREATED);
    }
}
