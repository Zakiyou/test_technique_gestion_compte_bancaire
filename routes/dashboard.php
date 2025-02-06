<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAuthenticated;
use App\Http\Middleware\CheckAdmin;

use App\Http\Controllers\CompteBancaireController;
use App\Http\Controllers\OperationController;

Route::prefix('dashboard')->middleware(CheckAuthenticated::class, CheckAdmin::class)->group(function () {
    

    Route::get('/comptes-actifs', [CompteBancaireController::class, 'indexActifs'])->name('comptes.actifs');

    Route::get('/comptes-inactifs', [CompteBancaireController::class, 'indexInactifs'])->name('comptes.inactifs');

    Route::post('/comptes/{id}/activer', [CompteBancaireController::class, 'activer'])->name('comptes.activer');

    Route::post('/comptes/{id}/desactiver', [CompteBancaireController::class, 'desactiver'])->name('comptes.desactiver');

    Route::get('/comptes/{id}/gestionnaire', [CompteBancaireController::class, 'voirGestionnaire'])->name('comptes.gestionnaire');
});

// Routes spécifiques avec uniquement CheckAuthenticated
Route::prefix('dashboard')->middleware(CheckAuthenticated::class)->group(function () {
    Route::get('/', [CompteBancaireController::class, 'index'])->name('dashboard_home');
    Route::post('/add_compte', [CompteBancaireController::class, 'store'])->name('comptes.store');

    Route::get('/operations/create', [OperationController::class, 'create'])->name('operations.create');

    Route::post('/operations/store', [OperationController::class, 'store'])->name('operations.store');

    Route::get('/operations/{compte_id}', [OperationController::class, 'index'])->name('operations.index');
});
