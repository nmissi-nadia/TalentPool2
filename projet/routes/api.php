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

// Gestion des Annonces pass
Route::group([
    "middleware" => ["auth:api", "check.role:recruteur"]
], function() {
    Route::post("annonces", [AnnonceController::class, "store"]); // Ajouter une annonce
    Route::put("annonces/{id}", [AnnonceController::class, "update"]); // Modifier une annonce
    Route::delete("annonces/{id}", [AnnonceController::class, "destroy"]); // Supprimer une annonce
});

// Candidatures
Route::group([
    "middleware" => ["auth:api", "check.role:candidat"]
], function() {
    Route::post("candidatures", [CandidatureController::class, "store"]); // Postuler à une annonce
    Route::delete("candidatures/{id}", [CandidatureController::class, "destroy"]); // Retirer une candidature
    // afficher les candidature que l'utilisateur seul qui  a postulé
    Route::get("candidatures", [CandidatureController::class, "index"]); 
    Route::get("candidatures/{id}", [CandidatureController::class, "show"]); 
    Route::get("candidatures/candidat/{candidatId}", [CandidatureController::class, "getByCandidat"]); // Récupérer les candidatures par candidat
});

// Suivi des Candidatures
Route::group([
    "middleware" => ["auth:api", "check.role:recruteur"]
], function() {
    Route::put("candidatures/{id}/status", [CandidatureController::class, "updateStatus"]); 
    Route::get("candidatures/annonce/{annonceId}", [CandidatureController::class, "getByAnnonce"]); // Récupérer les candidatures par annonce
});

// Récupérer les annonces pour les candidats
Route::group([
    "middleware" => ["auth:api", "check.role:candidat"]
], function() {
    Route::get("annonces", [AnnonceController::class, "index"]); 
    Route::get("annonces/{id}", [AnnonceController::class, "show"]); 
});

// Statistiques et Rapports
Route::group([
    "middleware" => ["auth:api", "check.role:admin,recruteur"]
], function() {
    Route::get("stats/annonces", [AnnonceController::class, "getStats"]); 
    Route::get("stats/candidatures", [CandidatureController::class, "getStats"]);
});