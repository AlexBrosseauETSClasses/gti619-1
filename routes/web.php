<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SecuritySettingsController;
use App\Models\Client;
use App\Http\Controllers\ClientController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::post('/client', [ClientController::class, 'store'])->name('client.store');

Route::get('/client/create-affaire', function () {
    return view('client.create-affaire');
})->name('clients.affaires.create');
//Create résidentiel
Route::get('/client/create-residentiel', function () {
    return view('client.create-residentiel');
})->name('clients.residentiel.create');
//Voir les client residentiels
Route::get('/admin/residentiels', function () {
    $clients = Client::where('type', 'residentiel')->get();
    return view('admin.residentiels', compact('clients'));
})->middleware(['auth', 'role:Administrateur'])->name('clients.residentiels');
//Voir les clients affaire
Route::get('/admin/affaires', function () {
    $clients = \App\Models\Client::where('type', 'affaire')->get();
    return view('admin.affaires', compact('clients'));
})->middleware(['auth', 'role:Administrateur'])->name('clients.affaires');
//edit client
Route::get('/client/{id}/edit', [ClientController::class, 'edit'])->name('client.edit');
//Destroy client
Route::delete('/client/{id}', [ClientController::class, 'destroy'])->name('client.destroy');
//Client Update
Route::put('/client/{id}', [ClientController::class, 'update'])->name('client.update');
// Routes d'administration sécurisées
Route::middleware(['auth', 'is-admin'])->group(function () {
    Route::get('/admin/security-settings', [SecuritySettingsController::class, 'edit'])->name('security.edit');
    Route::post('/admin/security-settings', [SecuritySettingsController::class, 'update'])->name('security.update');
});

require __DIR__.'/auth.php';

