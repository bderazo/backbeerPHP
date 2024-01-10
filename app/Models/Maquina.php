<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maquina extends Model
{
    use HasFactory,Uuid;
    protected $table = 'maquina';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'tipo_cerveza',
        'ubicacion',
        'precio',
        'cantidad',
        'estado',
        'id_comercio',
    ];

    public function comercio()
    {
        return $this->belongsTo(Comercio::class);
    }

    public function consumos()
    {
        return $this->hasMany(Consumo::class, 'id_maquina');
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
