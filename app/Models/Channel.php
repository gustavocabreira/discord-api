<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Channel extends Model
{
    use HasFactory;

    protected $table = 'channels';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'guild_id',
    ];

    public function guild(): BelongsTo
    {
        return $this->belongsTo(Guild::class, 'guild_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'channel_id', 'id');
    }
}
