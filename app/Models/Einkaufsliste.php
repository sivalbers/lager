<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Einkaufsliste extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'debitor_id',
        'abladestelle_id',
        'lagerort_id',
        'artikelnr',
        'menge',
        'kommentar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function debitor()
    {
        return $this->belongsTo(Debitor::class);
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
