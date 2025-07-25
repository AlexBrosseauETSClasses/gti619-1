<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
Route::middleware(['auth'])->group(function () {

    //Route::resource('client', ClientController::class);
    Route::get('/client/{id}/edit', [ClientController::class, 'edit'])->name('client.edit');

    Route::patch('/client/{id}', [ClientController::class, 'update'])->name('client.update');

    Route::delete('/client/{id}', [ClientController::class, 'destroy'])->name('client.destroy');


    Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'role:Administrateur'])
    ->name('admin.dashboard');

    Route::middleware('role:Préposé aux clients résidentiels|Administrateur')->group(function () {
        Route::get('/clients/residentiels', [ClientController::class, 'residentiels'])->name('clients.residentiels');
    });

    Route::middleware('role:Préposé aux clients d’affaire|Administrateur')->group(function () {
        Route::get('/clients/affaires', [ClientController::class, 'affaires'])->name('clients.affaires');
    });
    Route::get('/client/create-residentiel', function () {
        return view('client.create-residentiel');
    })->name('client.create-residentiel');

    Route::get('/client/create-affaire', function () {
        return view('client.create-affaire');
    })->name('client.create-affaire');
    Route::get('/clients/residentiels', [ClientController::class, 'residentiels'])->name('clients.residentiels');
    Route::get('/clients/affaires', [ClientController::class, 'affaires'])->name('clients.affaires');
    Route::post('/client', [ClientController::class, 'store'])->name('client.store');

});

