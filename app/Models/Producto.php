<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    use HasFactory, Uuid;
    protected $table = 'producto';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'comercio_id',
        'nombre',
        'descripcion',
        'tipo_producto',
        'categoria_producto_id',
        'estado',
        'codigo_barras',
        'tipo_impuesto',
        'registrado_por',
    ];

    public function comercio_id(): BelongsTo
    {
        return $this->belongsTo(Comercio::class, 'comercio_id');
    }

    public function categoria_producto_id(): BelongsTo
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_producto_id');
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