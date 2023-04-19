<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TipoComercio extends Model
{
    use HasFactory;
    protected $table = 'tipo_comercio';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable =[
        'nombre_tipo',
        'codigo',
        'extra_data'
    ];

    public function comercio(): HasMany
    {
        return $this->hasMany(Comercio::class);
    }
}
