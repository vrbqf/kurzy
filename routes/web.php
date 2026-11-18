<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\EnrollmentController;


Route::get('/', [CourseController::class, 'index']);
// CRUD pro kurzy a účastníky (můžeš využít resource routy)
Route::resource('courses', CourseController::class);
Route::resource('participants', ParticipantController::class);

// Přihlášení účastníka do kurzu
Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
