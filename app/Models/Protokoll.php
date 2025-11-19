<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Protokoll extends Model
{
    use HasFactory;

    protected $table = 'protokoll';

    protected $fillable = [
        'datum_zeit',
        'user_id',
        'artikelnr',
        'ablade­stelle_id',
        'lagerort_id',
        'lagerplatz',
        'menge',
        'buchungsgrund_id',
        'bemerkung',
    ];

    protected $casts = [
        'datum_zeit' => 'datetime',
        'menge' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'artikelnr', 'artikelnr');
    }

    public function abladestelle()
    {
        return $this->belongsTo(Abladestelle::class, 'abladestelle_id');
    }

    public function lagerort()
    {
        return $this->belongsTo(Lagerort::class, 'lagerort_id');
    }

    public function buchungsgrund()
    {
        return $this->belongsTo(Buchungsgrund::class, 'buchungsgrund_id');
    }
}
