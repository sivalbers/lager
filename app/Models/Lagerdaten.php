<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lagerdaten extends Model
{
    use HasFactory;

    protected $table = 'lagerdaten';

    protected $fillable = [
        'artikelnr',
        'lagernr',
        'lagerplatz',
        'bestand'
    ];

    public $incrementing = false; // Da kein "id"-Feld

    // Beziehungen
    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'artikelnr', 'artikelnr');
    }

    public function lagerort()
    {
        return $this->belongsTo(Lagerort::class, 'lagernr', 'nr');
    }
}
