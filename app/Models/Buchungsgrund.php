<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buchungsgrund extends Model
{
    protected $table = 'buchungsgrund';

    protected $fillable = [
        'bezeichnung',
    ];

    public function protokolle(): HasMany
    {
        return $this->hasMany(Protokoll::class, 'buchungsgrund_id');
    }
}
