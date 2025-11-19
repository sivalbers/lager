<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikeleinrichtung extends Model
{
    use HasFactory;

    protected $table = 'artikeleinrichtung';

    protected $fillable = [
        'artikelnr',
        'abladestelle_id',
        'lagerort',
        'mindestbestand',
        'bestellmenge',
        'abladestellenspezifisch',
    ];

    protected $casts = [
        'abladestellenspezifisch' => 'boolean'
    ];

    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'artikelnr', 'artikelnr');
    }

    public function abladestelle()
    {
        return $this->belongsTo(Abladestelle::class, 'abladestelle_id');
    }
}
