<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recht extends Model
{
    use HasFactory;

    protected $table = 'recht';



    protected $fillable = [
        'rechtegruppen_id',
        'berechtigung_id',
        'kommentar'
    ];

    public function rechtegruppe()
    {
        return $this->belongsTo(Rechtegruppe::class, 'rechtegruppen_id');
    }

    public function berechtigung()
    {
        return $this->belongsTo(Berechtigung::class, 'berechtigung_id');
    }
}
