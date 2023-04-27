<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable implements JWTSubject, CanResetPassword
{
    use HasFactory, Uuid, Notifiable;
    protected $table = 'usuarios';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'nombres',
        'apellidos',
        'correo',
        'password',
        'rol',
        'identificacion',
        'registrado_por',
    ];

    protected $hidden = [
        'password',


    ];

    public function userTarjeta(): HasMany
    {
        return $this->hasMany(UserTarjeta::class);
    }

    public function pagoTarjetas(): HasMany
    {
        return $this->hasMany(PagoTarjetas::class);
    }

    public function planTarjetas(): HasMany
    {
        return $this->hasMany(PlanTarjetas::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}