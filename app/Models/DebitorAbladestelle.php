<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DebitorAbladestelle extends Model
{
    protected $table = 'debitor_abladestellen';

    protected $fillable = [
        'user_id',
        'abladestelle_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function abladestelle(): BelongsTo
    {
        return $this->belongsTo(Abladestelle::class);
    }
}
