<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas para Profissionais
Route::middleware(['auth', 'verified'])->group(function () {
    // Perfil Profissional
    Route::get('/professional/create', [ProfessionalController::class, 'create'])->name('professional.create');
    Route::post('/professional', [ProfessionalController::class, 'store'])->name('professional.store');
    Route::get('/professional', [ProfessionalController::class, 'show'])->name('professional.show');
    Route::get('/professional/edit', [ProfessionalController::class, 'edit'])->name('professional.edit');
    Route::patch('/professional', [ProfessionalController::class, 'update'])->name('professional.update');

    // Portfólio
    Route::post('/professional/portfolio', [ProfessionalController::class, 'addPortfolioPhoto'])->name('professional.portfolio.add');
    Route::delete('/professional/portfolio/{photo}', [ProfessionalController::class, 'deletePortfolioPhoto'])->name('professional.portfolio.delete');

    // Serviços
    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('/', [ServiceController::class, 'store'])->name('services.store');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::patch('/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    });
});

require __DIR__.'/auth.php';
