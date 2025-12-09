<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Importprotokoll extends Model
{
    protected $table = 'importprotokoll';

    protected $fillable = [
        'debitornr',
        'lieferscheinnr',
        'artikelnr',
    ];

    // Beziehungen
    public function debitor(): BelongsTo
    {
        return $this->belongsTo(Debitor::class, 'debitornr', 'nr');
    }

    public function artikel(): BelongsTo
    {
        return $this->belongsTo(Artikel::class, 'artikelnr', 'artikelnr');
    }
}
