<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venta extends Model
{
    use HasFactory,Uuid;
    protected $table = 'venta';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'id_cliente',
        'total',
        'precio',
        'tipo_pago',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function consumos()
    {
        return $this->hasMany(Consumo::class, 'id_venta');
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
