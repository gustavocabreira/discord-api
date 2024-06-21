<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Models\Channel;
use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    public function store(Guild $guild, Channel $channel, StoreMessageRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $message = $channel
            ->messages()
            ->create($payload)
            ->load(['sender', 'channel.guild']);

        return response()->json($message, Response::HTTP_CREATED);
    }
}
