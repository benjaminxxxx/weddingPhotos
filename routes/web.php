<?php

use App\Http\Controllers\InvitadoController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/panel', [InvitadoController::class, 'panel'])->name('invitado.panel');
Route::get('/expirado', function () {
    return view('invitado.expirado');
})->name('invitado.expirado');
Route::get('/settings/boda', function () {
    return view('settings');
})->middleware(['auth', 'verified'])->name('settings.boda');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/{uuid}/mesa/{mesa}', [InvitadoController::class, 'acceder']);

require __DIR__ . '/auth.php';
