<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berechtigung extends Model
{
    use HasFactory;

    protected $table = 'berechtigung';

    public $timestamps = false; // <–– wichtig, damit Laravel keine Spalten erwartet

    protected $fillable = [
        'bezeichnung',
        'kommentar'
    ];

    public function rechte()
    {
        return $this->hasMany(Recht::class, 'berechtigung_id');
    }
}
