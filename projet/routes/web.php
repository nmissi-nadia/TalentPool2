<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CandidatureController;
use L5Swagger\Http\Controllers\SwaggerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification
Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/password/reset', function () {
    return view('auth.password-reset');
});

// Routes pour les annonces
Route::get('/annonces', function () {
    return view('annonces.index');
});

Route::get('/annonces/create', function () {
    return view('annonces.create');
});

Route::get('/annonces/edit/{id}', function ($id) {
    return view('annonces.create');
});

Route::get('/annonces/{id}', function ($id) {
    return view('annonces.show');
});

// Routes pour le tableau de bord
Route::get('/dashboard', function () {
    return view('dashboard.index');
});

Route::get('/dashboard/candidat', function () {
    return view('dashboard.candidat');
});

Route::get('/dashboard/recruteur', function () {
    return view('dashboard.recruteur');
});

Route::get('/dashboard/admin', function () {
    return view('dashboard.admin');
});
// Routes pour les candidatures
// Routes pour les candidatures
// Route::middleware(['auth'])->group(function () {
    // Routes accessibles uniquement aux candidats
    // Route::middleware(['checkrole:candidat'])->group(function () {
        Route::get('/candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
        Route::get('/candidatures/create', [CandidatureController::class, 'create'])->name('candidatures.create');
        Route::post('/candidatures', [CandidatureController::class, 'store'])->name('candidatures.store');
        Route::get('/candidatures/edit/{id}', [CandidatureController::class, 'edit'])->name('candidatures.edit');
        Route::put('/candidatures/{id}', [CandidatureController::class, 'update'])->name('candidatures.update');
        Route::delete('/candidatures/{id}', [CandidatureController::class, 'destroy'])->name('candidatures.destroy');
    // });
// });
