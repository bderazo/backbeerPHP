<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pulsera extends Model
{
    use HasFactory,Uuid;
    protected $table = 'pulsera';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'id_cliente',
        'cupo_maximo',
        'estado',
        'tipo_sensor',
        'codigo_sensor',
        'usuario_registra',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_cliente');
    }

    public function usuario_registra()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registra');
    }
    public function consumos()
    {
        return $this->hasMany(Consumo::class, 'id_pulsera');
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
