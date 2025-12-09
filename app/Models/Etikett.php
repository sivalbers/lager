<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etikett extends Model
{
    protected $table = 'etiketten';

    protected $fillable = [
        'artikelnr',
        'abladestelle_id',
        'lagerort_id',
        'lagerplatz',
    ];

    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'artikelnr', 'artikelnr');
    }

    public function abladestelle()
    {
        return $this->belongsTo(Abladestelle::class);
    }

    public function lagerort()
    {
        return $this->belongsTo(Lagerort::class);
    }
}
