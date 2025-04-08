<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    //Register API (POST ,formdata)
    public function register(Request $request)
    {
        // validation de données
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:8",
            "role"=>"required|in:admin,candidat,recruteur"
        ]);
        
        // create user
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password,
            "role" => $request->role
        ]);
        
        // return response
        return response()->json([
            "status" => true,
            "message" => "inscription reussie",
            "user" => $user
        ]);
        
    }
    //Login API (POST ,formdata)
    public function login(Request $request)
    {
        // data validation
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        // JWTAuth
        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!empty($token)){
            // Récupérer l'utilisateur connecté
            $user = auth()->user();
            
            // Envoi de l'email de notification (si nécessaire)
            try {
                // Décommentez ces lignes si vous souhaitez envoyer un email de notification
                // Mail::to($user->email)->send(new LoginNotification($user, request()->ip()));
            } catch (\Exception $e) {
                // En cas d'erreur d'envoi, on log l'erreur mais on ne bloque pas la connexion
                // Log::error('Erreur lors de l\'envoi de l\'email de notification : ' . $e->getMessage());
            }

            return response()->json([
                "status" => true,
                "message" => "Connexion reussie",
                "token" => $token
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Invalid details"
        ]);
    }
    // Profile API (GET)
     public function profile()
     {
        $userdata = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $userdata
        ]);
     }
     //Refresh Token API (GET)
     public function refreshToken()
     {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            
            return response()->json([
                "status" => true,
                "message" => "Nouveau access token",
                "token" => $newToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Erreur lors du rafraîchissement du token"
            ], 401);
        }
     }
    //  Logout Api (GET)
    public function logout()
    {
        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "Deconnexion reussie"
        ]);
    }
    // Reset Password API (POST)
    public function resetPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email"
        ]);

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "Utilisateur non trouvé"
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            "status" => true,
            "message" => "Mot de passe reinitialisé avec succès"
        ]);
    }
}
