<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikel';

    protected $primaryKey = 'artikelnr';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'artikelnr',
        'bezeichnung',
        'einheit',
        'materialgruppe'
    ];

    public function einrichtungen()
    {
        return $this->hasMany(Artikeleinrichtung::class, 'artikelnr', 'artikelnr');
    }

    public function bestaende()
    {
        return $this->hasMany(Artikelbestand::class, 'artikelnr', 'artikelnr');
    }

    public function protokolle()
    {
        return $this->hasMany(Protokoll::class, 'artikelnr', 'artikelnr');
    }
}
