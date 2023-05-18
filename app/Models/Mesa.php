<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mesa extends Model
{
    use HasFactory, Uuid;
    protected $table = 'mesas';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'sucursal_id',
        'num_mesa',
        'ubicacion_x',
        'ubicacion_y',
        'num_personas',
        'estado',
    ];

    public function sucursal_id(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'id');
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