<?php

namespace App\Observers;

use App\Models\Guild;

class GuildObserver
{
    public function creating(Guild $guild): void
    {
        if (! $guild->owner_id) {
            $guild->owner_id = auth()->user()->id;
        }
    }
}
