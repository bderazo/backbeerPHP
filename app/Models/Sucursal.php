<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sucursal extends Model
{
    use HasFactory,Uuid;
    protected $table = 'sucursal';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'comercio_id',
        'nombre',
        'ruc',
        'direccion',
        'telefono',
        'whatsapp',
        'correo',
        'secuencial_facturas',
        'siguiente_factura',
        'reponsable',
        'estado',
        'pax_capacidad',
        'es_matriz',
        'registrado_por'
    ];


    public function comercio_id(): BelongsTo
    {
        return $this->belongsTo(Comercio::class, 'id');
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