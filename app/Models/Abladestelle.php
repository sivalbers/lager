<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abladestelle extends Model
{
    protected $table = 'abladestellen';

    protected $fillable = [
        'name1',
        'name2',
        'strasse',
        'plz',
        'ort',
        'lagerort',
        'debitor_nr'
    ];

    public function debitor()
    {
        return $this->belongsTo(Debitor::class, 'debitor_nr', 'nr');
    }
    
}
