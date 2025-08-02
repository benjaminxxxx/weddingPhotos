<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boda extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'nombre',
        'subida_activa',
        'fecha_expiracion',
        'ver_planning',
        'ver_galeria',
        'ver_principal'
    ];

    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }
}
