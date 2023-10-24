<?php

namespace App\Models;

// use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComercioMaquina extends Model
{
    // use HasFactory,Uuid;
    protected $table = 'comercio_maquinas';
    public $incrementing = false;
    // protected $keyType = 'uuid';
    protected $fillable = [
        'id_comercio',
        'id_maquina',
    ];

    
    public function id_comercio(): BelongsTo
    {
        return $this->belongsTo(Comercio::class, 'id');
    }

    public function id_maquina(): BelongsTo
    {
        return $this->belongsTo(Maquina::class, 'id');
    }

    
    public function comercioMaquina(): HasMany
    {
        return $this->hasMany(ComercioMaquina::class);
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