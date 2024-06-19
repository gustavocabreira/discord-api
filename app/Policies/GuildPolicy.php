<?php

namespace App\Policies;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GuildPolicy
{
    public function delete(User $user, Guild $guild): Response
    {
        return $user->id === $guild->owner_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function createChannel(User $user, Guild $guild): Response
    {
        return $user->id === $guild->owner_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
