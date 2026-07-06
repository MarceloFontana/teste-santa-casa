<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::resource('usuarios', UserController::class)
            ->except('show')
            ->names('users')
            ->parameters(['usuarios' => 'user']);
        Route::resource('permissoes', PermissionController::class)
            ->except('show')
            ->names('permissions')
            ->parameters(['permissoes' => 'permission']);
    });

    Route::get('/modulos/setores-hospitalares', fn () => view('modules.show', ['title' => 'Setores Hospitalares']))
        ->middleware('permission:setores-hospitalares')
        ->name('modules.setores-hospitalares');
    Route::get('/modulos/especialidades-medicas', fn () => view('modules.show', ['title' => 'Especialidades Médicas']))
        ->middleware('permission:especialidades-medicas')
        ->name('modules.especialidades-medicas');
    Route::get('/modulos/equipamentos', fn () => view('modules.show', ['title' => 'Equipamentos']))
        ->middleware('permission:equipamentos')
        ->name('modules.equipamentos');
    Route::get('/modulos/unidades-assistenciais', fn () => view('modules.show', ['title' => 'Unidades Assistenciais']))
        ->middleware('permission:unidades-assistenciais')
        ->name('modules.unidades-assistenciais');
});

require __DIR__.'/auth.php';
