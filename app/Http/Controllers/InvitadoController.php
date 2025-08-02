<?php

namespace App\Http\Controllers;

use App\Models\Boda;
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
        if (!session()->has('boda_uuid') || !session()->has('upload_token')) {
            return view('invitado.expirado'); // mostrar mensaje para escanear de nuevo
        }
        return view('invitado.panel');
    }
}
