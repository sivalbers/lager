<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debitor extends Model
{
    protected $table = 'debitoren';

    protected $primaryKey = 'nr';
    public $incrementing = false;

    protected $fillable = [
        'nr',
        'name',
        'netzregion',
    ];


    public function abladestellen()
    {
        return $this->hasMany(Abladestelle::class, 'debitor_nr');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'debitor_nr');
    }
}
