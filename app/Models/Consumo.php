<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consumo extends Model
{
    use HasFactory,Uuid;
    protected $table = 'consumo';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'id_pulsera',
        'total',
        'precio',
        'id_maquina',
        'estado',
        'id_venta'
    ];

    public function maquina()
    {
        return $this->belongsTo(Maquina::class, 'id_maquina');
    }

    public function pulsera()
    {
        return $this->belongsTo(Pulsera::class, 'id_pulsera');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
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
