<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExcelController;

/* Home */
Route::middleware('auth')->group(function () {
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

    Route::post('/students/update/{id}', [StudentController::class, 'update']);
    Route::get('/students/show/{id}', [StudentController::class, 'show']);
    Route::post('/upload-excel', [ExcelController::class, 'uploadExcel']);
});

// 😄 Authentication Routes

Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'registerStore']);

Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::post('/login', [AuthController::class, 'loginStore']);

Route::get('/logout', [AuthController::class, 'logout']);
