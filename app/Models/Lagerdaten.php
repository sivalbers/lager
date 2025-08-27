<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lagerdaten extends Model
{
    use HasFactory;

    protected $table = 'lagerdaten';

    // Wichtig: Laravel erwartet normalerweise eine einzelne ID-Spalte
    public $incrementing = false;
    protected $primaryKey = null; // Deaktiviert standardmäßiges Primärschlüsselverhalten
    protected $keyType = 'string'; // Falls deine Keys Strings sind, sonst 'int'

    protected $fillable = [
        'artikelnr',
        'lagernr',
        'lagerplatz',
        'bestand'
    ];

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
