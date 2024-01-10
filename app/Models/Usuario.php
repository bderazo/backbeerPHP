<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'telefono',
        'correo',
        'password',
        'rol',
        'tipo_usuario',
        'identificacion',
        'puntos',
        'registrado_por'
    ];

    protected $hidden = [
        'password',
    ];

    public function pulsera()
    {
        return $this->hasOne(Pulsera::class, 'id_cliente');
    }

    public function pulserasRegistradas()
    {
        return $this->hasMany(Pulsera::class, 'usuario_registra');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_cliente');
    }
    public function beerRfid(): HasMany
    {
        return $this->hasMany(BeerRfid::class);
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
