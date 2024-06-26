<?php

namespace App\Models;

use App\Observers\InviteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(InviteObserver::class)]
class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'guild_id',
        'creator_id',
        'name',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
