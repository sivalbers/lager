<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikel';

    protected $primaryKey = 'artikelnr';
    public $incrementing = false; // Kein Auto-Increment
    protected $keyType = 'string'; // Primärschlüssel ist Text

    protected $fillable = [
        'artikelnr',
        'bezeichnung',
        'einheit',
    ];
}
