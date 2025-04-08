<?php

use Illuminate\Support\Facades\Route;

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
