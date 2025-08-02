<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'boda_id',
        'galeria_id',
        'numero',
        'tipo',
        'archivo',
        'aprobado',
        'upload_token',
        'nombre_opcional',
        'mensaje_opcional',
        'galeria',
        'oficial',
        'likes',
        'unlikes', 
        'hearts'
    ];

    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }
    public function galeria()
    {
        return $this->belongsTo(Galeria::class);
    }
    protected $casts = [
        'likes' => 'integer',
        'unlikes' => 'integer',
        'hearts' => 'integer',
    ];

    public function boda()
    {
        return $this->belongsTo(Boda::class, 'boda_id', 'uuid');
    }

    public function userReactions()
    {
        return $this->hasMany(UserReaction::class);
    }

    public function userLikes()
    {
        return $this->userReactions()->where('type', 'likes');
    }
}
