<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AnnonceController;
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
})->name('home');

// Routes d'authentification
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [ApiController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [ApiController::class, 'register']);

Route::post('/logout', [ApiController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/password/reset', function () {
    return view('auth.password-reset');
})->name('password.reset');
Route::post('/password/reset', [ApiController::class, 'resetPassword']);

Route::get('/profile', [ApiController::class, 'profile'])->middleware('auth')->name('profile');

// Routes pour les annonces
Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
Route::get('/annonces/create', function () {
    return view('annonces.create');
})->middleware(['auth', 'check.role:recruteur'])->name('annonces.create');
Route::post('/annonces', [AnnonceController::class, 'store'])->middleware(['auth', 'check.role:recruteur'])->name('annonces.store');
Route::get('/annonces/{id}', [AnnonceController::class, 'show'])->name('annonces.show');
Route::get('/annonces/edit/{id}', function ($id) {
    return view('annonces.create', ['id' => $id]);
})->middleware(['auth', 'check.role:recruteur'])->name('annonces.edit');
Route::put('/annonces/{id}', [AnnonceController::class, 'update'])->middleware(['auth', 'check.role:recruteur'])->name('annonces.update');
Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy'])->middleware(['auth', 'check.role:recruteur,admin'])->name('annonces.destroy');


// Routes pour le tableau de bord - middleware auth
Route::middleware(['auth'])->group(function () {
    // Dashboard général
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard.index');

    // Routes accessibles uniquement aux candidats
    Route::middleware(['check.role:candidat'])->group(function () {
        Route::get('/dashboard/candidat', function () {
            return view('dashboard.candidat');
        })->name('dashboard.candidat');
        
        // Routes pour les candidatures (candidat)
        Route::get('/candidatures', [CandidatureController::class, 'getByCandidat'])->name('candidatures.index');
        Route::post('/candidatures', [CandidatureController::class, 'store'])->name('candidatures.store');
        Route::delete('/candidatures/{id}', [CandidatureController::class, 'destroy'])->name('candidatures.destroy');
    });

    // Routes accessibles uniquement aux recruteurs
    Route::middleware(['check.role:recruteur'])->group(function () {
        Route::get('/dashboard/recruteur', function () {
            return view('dashboard.recruteur');
        })->name('dashboard.recruteur');
        
        // Gestion des candidatures pour les recruteurs
        Route::get('/candidatures/annonce/{annonceId}', [CandidatureController::class, 'getByAnnonce'])->name('candidatures.by-annonce');
        Route::put('/candidatures/{id}/status', [CandidatureController::class, 'updateStatus'])->name('candidatures.update-status');
    });

    // Routes accessibles uniquement aux administrateurs
    Route::middleware(['check.role:admin'])->group(function () {
        Route::get('/dashboard/admin', function () {
            return view('dashboard.admin');
        })->name('dashboard.admin');
        
        // Routes statistiques pour admin
        Route::get('/stats/annonces', [AnnonceController::class, 'getStats'])->name('stats.annonces');
        Route::get('/stats/candidatures', [CandidatureController::class, 'getStats'])->name('stats.candidatures');
        
        // Gestion des candidatures pour l'admin
        Route::get('/candidatures', [CandidatureController::class, 'index'])->name('admin.candidatures.index');
        Route::get('/candidatures/{id}', [CandidatureController::class, 'show'])->name('admin.candidatures.show');
        Route::delete('/candidatures/admin/{id}', [CandidatureController::class, 'destroy'])->name('admin.candidatures.destroy');
    });
});
