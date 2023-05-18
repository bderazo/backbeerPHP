<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permisos extends Model
{
    use HasFactory,Uuid;
    protected $table = 'permisos';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'extra_data'
    ];

    public function usuarioPermiso(): HasMany
    {
        return $this->hasMany(UsuarioPermiso::class);
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