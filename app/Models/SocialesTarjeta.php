<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialesTarjeta extends Model
{
    use HasFactory,Uuid;
    protected $table = 'sociales_tarjeta';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'user_tarjeta_id',
        'estado',
        'text_label',
        'url_label',
        'tipo_social',
        'icon_social',
    ];

    public function user_tarjeta_id(): BelongsTo
    {
        return $this->belongsTo(UserTarjeta::class, 'id');

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