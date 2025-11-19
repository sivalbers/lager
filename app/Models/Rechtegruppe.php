<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rechtegruppe extends Model
{
    use HasFactory;

    protected $table = 'rechtegruppe';

    protected $fillable = [
        'name'
    ];

    public function rechte()
    {
        return $this->hasMany(Recht::class, 'rechtegruppen_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'rechtegruppe_id');
    }
}
