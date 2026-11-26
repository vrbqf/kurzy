<?php

// Načtu kontroler pro kurzy
use App\Http\Controllers\CourseController;
// Načtu kontroler pro přihlášky
use App\Http\Controllers\EnrollmentController;
// Načtu kontroler pro účastníky
use App\Http\Controllers\ParticipantController;
// Načtu fasádu Route pro definování HTTP cest aplikace
use Illuminate\Support\Facades\Route;

// Hlavní stránka (/) zavolá metodu index v CourseControlleru a pojmenuji ji 'home'
Route::get('/', [CourseController::class, 'index'])->name('home');

// Vygeneruji routy pro kurzy, ale omezím je jen na index, store a destroy
Route::resource('courses', CourseController::class)->only(['index', 'store', 'destroy']);
// Vygeneruji routy pro účastníky a omezím je jen na store a destroy
Route::resource('participants', ParticipantController::class)->only(['store', 'destroy']);

// POST požadavek na /enrollments vytvoří novou přihlášku přes metodu store
Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
// PATCH požadavek na /enrollments/{enrollment}/status změní stav konkrétní přihlášky přes updateStatus
Route::patch('/enrollments/{enrollment}/status', [EnrollmentController::class, 'updateStatus'])->name('enrollments.status');
