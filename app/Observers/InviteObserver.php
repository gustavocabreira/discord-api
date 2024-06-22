<?php

namespace App\Observers;

use App\Models\Invite;

class InviteObserver
{
    public function creating(Invite $invite): void
    {
        if (! $invite->creator_id) {
            $invite->creator_id = auth()->id();
        }
    }
}
