<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abladestelle extends Model
{
    protected $table = 'abladestellen';

    protected $fillable = [
        'debitor_nr',
        'name',
        'name2',
        'strasse',
        'plz',
        'ort',
        'kostenstelle',
        'bestellrhythmus',
        'naechstes_belieferungsdatum'

    ];

    protected $casts = [
        'naechstes_belieferungsdatum' => 'date'
    ];

    public function debitor()
    {
        return $this->belongsTo(Debitor::class, 'debitor_nr');
    }

    public function lagerorte()
    {
        return $this->hasMany(Lagerort::class, 'abladestelle_id');
    }

    public function einrichtungen()
    {
        return $this->hasMany(Artikeleinrichtung::class, 'abladestelle_id');
    }

    public function bestaende()
    {
        return $this->hasMany(Artikelbestand::class, 'abladestelle_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'abladestelle_id');
    }

}
