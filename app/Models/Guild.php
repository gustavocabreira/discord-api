<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
