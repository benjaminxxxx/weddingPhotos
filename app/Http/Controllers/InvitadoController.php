<?php

namespace App\Http\Controllers;

use App\Models\Boda;
use Auth;
use Illuminate\Http\Request;
use Session;
use Str;

class InvitadoController extends Controller
{
    public function acceder($uuid, $mesa)
    {
        $boda = Boda::where('uuid', $uuid)->first();

        if (!$boda) {
            return view('invitado.error', ['mensaje' => 'La boda no existe.']);
        }

        // Crear un token de sesión único para este dispositivo
        $uploadToken = Str::uuid();

        // Guardar en la sesión del navegador (no requiere login)
        Session::put('boda_uuid', $uuid);
        Session::put('mesa', $mesa);
        Session::put('upload_token', $uploadToken);

        return redirect()->route('invitado.panel'); // o vista principal
    }

    public function panel()
    {
        // Si hay un usuario autenticado, redirigirlo usando la primera boda
        if (Auth::check()) {
            $boda = Boda::first();
            if (!$boda) {
                return view('invitado.error', ['mensaje' => 'No se encontró ninguna boda disponible.']);
            }

            // Simula el acceso del invitado con valores por defecto
            $this->acceder($boda->uuid, 1);
        }

        // Si no hay sesión, mostrar mensaje para volver a escanear
        if (!session()->has('boda_uuid') || !session()->has('upload_token')) {
            return view('invitado.expirado'); // Mostrar aviso
        }

        // Si todo está bien, mostrar el panel normal
        return view('invitado.panel');
    }

}
