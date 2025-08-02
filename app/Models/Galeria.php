<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    use HasFactory;

    protected $fillable = [
        'boda_id',
        'upload_token',
        'mesa',
        'mensaje',
        'likes',
        'unlikes',
        'hearts',
    ];

    // Relación: una galería pertenece a una boda
    public function boda()
    {
        return $this->belongsTo(Boda::class);
    }

    // Relación: una galería tiene muchos archivos (fotos/videos)
    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }
}
