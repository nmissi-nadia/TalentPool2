<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AnnonceController;
use App\Http\Controllers\Api\CandidatureController;
use L5Swagger\Http\Controllers\SwaggerController;

Route::get('/api/documentation', [SwaggerController::class, 'api']);
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentification et Sécurité
Route::post("register", [ApiController::class, "register"]);
Route::post("login", [ApiController::class, "login"]);
Route::post("password/reset", [ApiController::class, "resetPassword"]);
// Déconnexion avec middleware auth:api
Route::post("logout", [ApiController::class, "logout"])->middleware("auth:api");
// Profil utilisateur
Route::get("profile", [ApiController::class, "profile"])->middleware("auth:api");
// Rafraîchissement du token
Route::get("refresh-token", [ApiController::class, "refreshToken"])->middleware("auth:api");

// Gestion des Annonces pour les recruteurs
Route::group([
    "middleware" => ["auth:api", "check.role:recruteur"]
], function() {
    Route::post("annonces", [AnnonceController::class, "store"]); // Ajouter une annonce
    Route::put("annonces/{id}", [AnnonceController::class, "update"]); // Modifier une annonce
});

// Candidatures pour les candidats
Route::group([
    "middleware" => ["auth:api", "check.role:candidat"]
], function() {
    Route::post("candidatures", [CandidatureController::class, "store"]); // Postuler à une annonce
    Route::get("candidatures/candidat/{candidatId}", [CandidatureController::class, "getByCandidat"]); // Récupérer les candidatures par candidat
    Route::delete("candidatures/{id}", [CandidatureController::class, "destroy"]); // Retirer sa propre candidature
});

// Suivi des Candidatures pour les recruteurs
Route::group([
    "middleware" => ["auth:api", "check.role:recruteur"]
], function() {
    Route::put("candidatures/{id}/status", [CandidatureController::class, "updateStatus"]); 
    Route::get("candidatures/annonce/{annonceId}", [CandidatureController::class, "getByAnnonce"]); // Récupérer les candidatures par annonce
});

// Récupérer les annonces (accessible aux candidats, recruteurs et admin)
Route::group([
    "middleware" => ["auth:api", "check.role:candidat,recruteur,admin"]
], function() {
    Route::get("annonces", [AnnonceController::class, "index"]); 
    Route::get("annonces/{id}", [AnnonceController::class, "show"]); 
});

// Fonctionnalités Admin uniquement
Route::group([
    "middleware" => ["auth:api", "check.role:admin"]
], function() {
    Route::get("candidatures", [CandidatureController::class, "index"]); // Voir toutes les candidatures
    Route::get("candidatures/{id}", [CandidatureController::class, "show"]); // Voir le détail d'une candidature
    Route::delete("annonces/{id}", [AnnonceController::class, "destroy"]); // Supprimer une annonce
    Route::delete("candidatures/admin/{id}", [CandidatureController::class, "destroy"]); // Supprimer une candidature en tant qu'admin
});

// Statistiques et Rapports
Route::group([
    "middleware" => ["auth:api", "check.role:admin,recruteur"]
], function() {
    Route::get("stats/annonces", [AnnonceController::class, "getStats"]); 
    Route::get("stats/candidatures", [CandidatureController::class, "getStats"]);
});