<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'debitor_nr',
        'rechtegruppe_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function debitor()
    {
        return $this->belongsTo(Debitor::class, 'debitor_nr');
    }

    public function rechtegruppe()
    {
        return $this->belongsTo(Rechtegruppe::class, 'rechtegruppe_id');
    }

    public function protokolle()
    {
        return $this->hasMany(Protokoll::class, 'user_id');
    }

    public function berechtigungen()
    {
        return $this->hasManyThrough(
            Berechtigung::class,
            Recht::class,
            'rechtegruppen_id',   // Fremdschlüssel in "recht"
            'id',                 // Fremdschlüssel in "berechtigung"
            'rechtegruppe_id',    // Lokaler Schlüssel in "users"
            'berechtigung_id'     // Lokaler Schlüssel in "recht"
        );
    }

    // Neue Beziehung:
    public function abladestellen()
    {
        return $this->belongsToMany(Abladestelle::class, 'debitor_abladestellen');
    }


    public function hasBerechtigung(string $bezeichnung): bool
    {
        return $this->berechtigungen->contains('bezeichnung', $bezeichnung);

    }


}
