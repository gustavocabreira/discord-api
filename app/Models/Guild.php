<?php

namespace App\Models;

use App\Observers\GuildObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([GuildObserver::class])]
class Guild extends Model
{
    use HasFactory;

    protected $table = 'guilds';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'owner_id',
        'logo_path',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id')->select('id', 'name');
    }

    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class, 'guild_id', 'id');
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }
}
