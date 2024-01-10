<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comercio extends Model
{
    use HasFactory,Uuid;
    protected $table = 'comercio';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'nombre_comercial',
        'razon_social',
        'ruc',
        'direccion',
        'telefono',
        'correo',
        'logo',
        'sitio_web',
        'estado',
    ];

    public function maquinas()
    {
        return $this->hasMany(Maquina::class, 'id_comercio');
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
