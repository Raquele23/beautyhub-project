<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isProfessional()) {
        return redirect()->route('professional.dashboard');
    }
    return redirect()->route('explore');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/explore', [ExploreController::class, 'index'])->name('explore');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cancelamento acessível para profissionais e clientes
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');
});

Route::middleware(['auth', 'verified', 'professional'])->group(function () {

    Route::get('/professional/dashboard', [ProfessionalController::class, 'dashboard'])->name('professional.dashboard');

    Route::get('/professional/create', [ProfessionalController::class, 'create'])->name('professional.create');
    Route::post('/professional', [ProfessionalController::class, 'store'])->name('professional.store');
    Route::get('/professional', [ProfessionalController::class, 'show'])->name('professional.show');
    Route::get('/professional/edit', [ProfessionalController::class, 'edit'])->name('professional.edit');
    Route::patch('/professional', [ProfessionalController::class, 'update'])->name('professional.update');

    Route::post('/professional/portfolio', [ProfessionalController::class, 'addPortfolioPhoto'])->name('professional.portfolio.add');
    Route::delete('/professional/portfolio/{photo}', [ProfessionalController::class, 'deletePortfolioPhoto'])->name('professional.portfolio.delete');

    Route::get('/professional/availability', [AvailabilityController::class, 'index'])->name('professional.availability');
    Route::post('/professional/availability', [AvailabilityController::class, 'save'])->name('professional.availability.save');

    Route::get('/professional/appointments', [ProfessionalController::class, 'appointments'])->name('professional.appointments');
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');

    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('/', [ServiceController::class, 'store'])->name('services.store');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::patch('/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    });
});

Route::middleware(['auth', 'verified', 'client'])->group(function () {
    Route::get('/client/dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');

    Route::get('/professional/{professional}/book/{service}', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/professional/{professional}/book/{service}', [AppointmentController::class, 'store'])->name('appointments.store');
});

Route::get('/professional/{professional}', [ProfessionalController::class, 'publicShow'])
    ->name('professional.public');

Route::get('/professional/{professional}/slots', [AppointmentController::class, 'slots'])
    ->name('appointments.slots');

require __DIR__.'/auth.php';