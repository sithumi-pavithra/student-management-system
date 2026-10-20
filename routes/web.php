<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Literal routes FIRST (so they are not caught by /students/{id})
    Route::get('/students/trash',  [StudentController::class, 'trash'])->name('students.trash');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');

    // Collection (list + create via AJAX modal)
    Route::get ('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');

    // Item routes (edit via AJAX modal, delete, restore, force delete)
    Route::put   ('/students/{id}',              [StudentController::class, 'update'])     ->name('students.update')     ->whereNumber('id');
    Route::delete('/students/{id}',              [StudentController::class, 'destroy'])    ->name('students.destroy')    ->whereNumber('id');
    Route::post  ('/students/{id}/restore',      [StudentController::class, 'restore'])    ->name('students.restore')    ->whereNumber('id');
    Route::delete('/students/{id}/force-delete', [StudentController::class, 'forceDelete'])->name('students.forceDelete')->whereNumber('id');
});