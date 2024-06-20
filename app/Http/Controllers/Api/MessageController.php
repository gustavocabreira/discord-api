<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    public function store(Guild $guild, Channel $channel, Request $request): JsonResponse
    {
        $message = $channel
            ->messages()
            ->create($request->only('content'))
            ->load(['sender', 'channel.guild']);

        return response()->json($message, Response::HTTP_CREATED);
    }
}
