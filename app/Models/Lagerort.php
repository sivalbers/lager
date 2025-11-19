<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lagerort extends Model
{
    protected $table = 'lagerorte';

    protected $fillable = [
        'abladestelle_id',
        'ort',
        'bezeichnung'
    ];

    public function abladestelle()
    {
        return $this->belongsTo(Abladestelle::class, 'abladestelle_id');
    }

    public function bestaende()
    {
        return $this->hasMany(Artikelbestand::class, 'lagerort_id');
    }

    public function getAbladestelleNameAttribute()
    {
        return $this->abladestelle?->name;
    }
}
