<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SecuritySettingsController;
use App\Models\Client;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ReauthController;
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
Route::middleware(['auth', 'role:Administrateur'])->group(function () {
    Route::get('/admin/security-settings', [SecuritySettingsController::class, 'edit'])->name('security.edit');
    Route::post('/admin/security-settings', [SecuritySettingsController::class, 'update'])->name('security.update');
});

Route::middleware(['auth', 'role:Administrateur'])->group(function () {
    Route::get('/admin/ajouter', [AdminUserController::class, 'create'])->name('admin.register');
    Route::post('/admin/ajouter', [AdminUserController::class, 'store'])->name('admin.user.store');
});

Route::middleware('role:Préposé aux clients résidentiels|Administrateur')->group(function () {
        Route::get('/clients/residentiels', [ClientController::class, 'residentiels'])->name('clients.residentiels');
    });

    Route::middleware('role:Préposé aux clients d’affaire|Administrateur')->group(function () {
        Route::get('/clients/affaires', [ClientController::class, 'affaires'])->name('clients.affaires');
    });

Route::middleware(['auth', 'reauth'])->group(function () {
    Route::get('/password/custom-reset', [NewPasswordController::class, 'showCustomResetForm'])->name('password.custom.reset');
    Route::post('/password/custom-reset', [NewPasswordController::class, 'updatePassword'])->name('password.custom.reset.submit');
});
Route::middleware('auth')->group(function () {
    Route::get('/reauth', [ReauthController::class, 'showForm'])->name('reauth.show');
    Route::post('/reauth', [ReauthController::class, 'reauthenticate'])->name('reauth.attempt');
});

require __DIR__.'/auth.php';

