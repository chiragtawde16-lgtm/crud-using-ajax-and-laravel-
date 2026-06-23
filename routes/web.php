<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

/* Home */
Route::get('/', [StudentController::class, 'index']);

/* Student Routes */
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students/store', [StudentController::class, 'store'])->name('students.store');

Route::get('/students/edit/{id}', [StudentController::class, 'edit'])->name('students.edit');
Route::post('/students/update/{id}', [StudentController::class, 'update'])->name('students.update');

Route::get('/students/delete/{id}', [StudentController::class, 'destroy'])->name('students.delete');
Route::get('/students/fetch', [StudentController::class, 'fetch']);