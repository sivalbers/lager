<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lagerort extends Model
{
    protected $table = 'lagerorte';
    protected $primaryKey = 'nr';

    protected $fillable = [
        'nr',
        'bezeichnung',
    ];
}
