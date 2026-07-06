<?php

use App\Http\Controllers\ProfileController;
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

    Route::get('/usuarios', function () {
        $users = collect([
            (object) ['id' => 1, 'name' => 'Admin Master', 'email' => 'admin@santacasa.org.br', 'role' => 'Administrador'],
            (object) ['id' => 2, 'name' => 'Maria Souza', 'email' => 'maria.souza@santacasa.org.br', 'role' => 'Colaborador'],
            (object) ['id' => 3, 'name' => 'João Pereira', 'email' => 'joao.pereira@santacasa.org.br', 'role' => 'Colaborador'],
        ]);

        return view('users.index', compact('users'));
    })->name('users.index');

    Route::get('/permissoes', function () {
        $permissions = collect([
            (object) ['id' => 1, 'name' => 'setores-hospitalares', 'label' => 'Setores Hospitalares'],
            (object) ['id' => 2, 'name' => 'especialidades-medicas', 'label' => 'Especialidades Médicas'],
            (object) ['id' => 3, 'name' => 'equipamentos', 'label' => 'Equipamentos'],
            (object) ['id' => 4, 'name' => 'unidades-assistenciais', 'label' => 'Unidades Assistenciais'],
        ]);

        return view('permissions.index', compact('permissions'));
    })->name('permissions.index');

    Route::get('/modulos/setores-hospitalares', fn () => view('modules.show', ['title' => 'Setores Hospitalares']))
        ->name('modules.setores-hospitalares');
    Route::get('/modulos/especialidades-medicas', fn () => view('modules.show', ['title' => 'Especialidades Médicas']))
        ->name('modules.especialidades-medicas');
    Route::get('/modulos/equipamentos', fn () => view('modules.show', ['title' => 'Equipamentos']))
        ->name('modules.equipamentos');
    Route::get('/modulos/unidades-assistenciais', fn () => view('modules.show', ['title' => 'Unidades Assistenciais']))
        ->name('modules.unidades-assistenciais');
});

require __DIR__.'/auth.php';
