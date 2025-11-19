<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Psp extends Model
{
    protected $table = 'psp';

    protected $fillable = [
        'netzregion',
        'kostenstelle',
        'artikel',
        'materialgruppe',
        'format',
        'beschreibung',
    ];
}
