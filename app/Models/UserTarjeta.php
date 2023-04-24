<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserTarjeta extends Model
{
    use HasFactory,Uuid;
    protected $table = 'user_tarjeta';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'user_id',
        'comercio_id',
        'estado',
        'img_perfil',
        'img_portada',
        'nombre',
        'profesion',
        'empresa',
        'acreditaciones',
        'telefono',
        'direccion',
        'correo',
        'sitio_web'
    ];


    public function user_id(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id');

    }

    public function comercio_id(): BelongsTo
    {
        return $this->belongsTo(Comercio::class, 'id');

    }

    public function socialesTarjeta(): HasMany
    {
        return $this->hasMany(SocialesTarjeta::class);
    }

    public function configuracionesTarjeta(): HasMany
    {
        return $this->hasMany(ConfiguracionesTarjeta::class);
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