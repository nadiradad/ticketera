<?php

use App\Http\Controllers\Admin\UserStaffController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\MisTicketsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepuestoController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:tecnico'])->group(function () {
        Route::get('/mis-tickets', [MisTicketsController::class, 'index'])->name('mis-tickets.index');
    });

    Route::resource('tickets', TicketController::class);

    Route::post('/tickets/{ticket}/repuestos', [TicketController::class, 'agregarRepuesto'])
        ->name('tickets.repuestos.agregar');

    Route::post('/tickets/{ticket}/historial', [TicketController::class, 'agregarHistorial'])
        ->name('tickets.historial.agregar');

    Route::post('/notificaciones/{id}/marcar-leida', function (string $id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->route('tickets.show', $notification->data['ticket_id']);
    })->name('notificaciones.marcar-leida');

    Route::middleware(['role:administrador,recepcionista'])->group(function () {
        Route::resource('clientes', ClienteController::class)->except(['show']);
        Route::resource('equipos', EquipoController::class)->except(['show']);
        Route::resource('repuestos', RepuestoController::class)->except(['show']);
    });

    Route::middleware(['role:administrador'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('staff', UserStaffController::class)->except(['show']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
