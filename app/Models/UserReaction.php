<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reaction_key',
        'user_token',
        'archivo_id', 
        'type',
        'boda_id', // Cambiar de boda_uuid a boda_id
        'mesa'
    ];

    public function archivo()
    {
        return $this->belongsTo(Archivo::class);
    }

    public function boda()
    {
        return $this->belongsTo(Boda::class); // Relación directa con boda_id
    }

    // Método estático para crear la clave de reacción
    public static function makeReactionKey($userToken, $archivoId)
    {
        return $userToken . '_' . $archivoId;
    }

    // Scope para filtrar por token de usuario
    public function scopeByUserToken($query, $userToken)
    {
        return $query->where('user_token', $userToken);
    }

    // Scope para filtrar por boda
    public function scopeByBoda($query, $bodaId)
    {
        return $query->where('boda_id', $bodaId);
    }
}
