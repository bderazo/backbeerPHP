<?php

namespace App\Models;

// use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaquinaVenta extends Model
{
    // use HasFactory,Uuid;
    protected $table = 'maquina_ventas';
    public $incrementing = false;
    // protected $keyType = 'uuid';
    protected $fillable = [
        'id_venta',
        'id_maquina',
        'cantidad',
        'precio',
    ];

    
    public function id_venta(): BelongsTo
    {
        return $this->belongsTo(Ventas::class, 'id');
    }

    public function id_maquina(): BelongsTo
    {
        return $this->belongsTo(Maquina::class, 'id');
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