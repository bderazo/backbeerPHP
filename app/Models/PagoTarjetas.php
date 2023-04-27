<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PagoTarjetas extends Model
{
    use HasFactory,Uuid;
    protected $table = 'tarjetas_comercio';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'usuarios_id',
        'estado',
        'forma_pago',
        'valor',
        'descripcion',
        'observaciones',
        'adjuntos',
        'tipo_plan'
    ];

    public function planTarjetas(): HasMany
    {
        return $this->hasMany(PlanTarjetas::class);
    }

    public function planTarjetasComercio(): HasMany
    {
        return $this->hasMany(PlanTarjetasComercio::class);
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