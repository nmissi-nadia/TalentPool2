<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    // Détermine si la requête est une requête API ou Web
    protected function isApiRequest(Request $request)
    {
        return $request->expectsJson() || $request->is('api/*');
    }

    /**
     * Inscription utilisateur
     * Gère à la fois les requêtes API et web
     */
    public function register(Request $request)
    {
        // Validation de données
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:8",
            "role" => "required|in:admin,candidat,recruteur"
        ]);
        
        // Create user
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => $request->role
        ]);
        
        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json([
                "status" => true,
                "message" => "Inscription réussie",
                "user" => $user
            ]);
        }
        
        // Pour les requêtes web, connecter l'utilisateur et rediriger
        Auth::login($user);
        
        // Rediriger selon le rôle
        switch($user->role) {
            case 'admin':
                return redirect()->route('dashboard.admin');
            case 'recruteur':
                return redirect()->route('dashboard.recruteur');
            case 'candidat':
                return redirect()->route('dashboard.candidat');
            default:
                return redirect()->route('dashboard.index');
        }
    }

    /**
     * Connexion utilisateur
     * Gère à la fois les requêtes API et web
     */
    public function login(Request $request)
    {
        // Validation des données
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $credentials = $request->only('email', 'password');

        // Pour les requêtes API, utiliser JWT
        if ($this->isApiRequest($request)) {
            $token = JWTAuth::attempt($credentials);

            if (!empty($token)) {
                return response()->json([
                    "status" => true,
                    "message" => "Connexion réussie",
                    "token" => $token
                ]);
            }

            return response()->json([
                "status" => false,
                "message" => "Identifiants invalides"
            ], 401);
        }
        
        // Pour les requêtes web, utiliser l'authentification de session
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Rediriger selon le rôle
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('dashboard.admin');
                case 'recruteur':
                    return redirect()->route('dashboard.recruteur');
                case 'candidat':
                    return redirect()->route('dashboard.candidat');
                default:
                    return redirect()->route('dashboard.index');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput($request->except('password'));
    }

    /**
     * Profil utilisateur
     * Gère à la fois les requêtes API et web
     */
    public function profile(Request $request)
    {
        $user = auth()->user();

        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json([
                "status" => true,
                "message" => "Données du profil",
                "data" => $user
            ]);
        }
        
        // Pour les requêtes web, renvoyer une vue
        return view('profile.index', compact('user'));
    }

    /**
     * Rafraîchir le token JWT (API uniquement)
     */
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

    /**
     * Déconnexion
     * Gère à la fois les requêtes API et web
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json([
                "status" => true,
                "message" => "Déconnexion réussie"
            ]);
        }
        
        // Pour les requêtes web, invalider la session et rediriger
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * Réinitialisation de mot de passe
     * Gère à la fois les requêtes API et web
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:8"
        ]);

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    "status" => false,
                    "message" => "Utilisateur non trouvé"
                ], 404);
            }
            
            return back()->withErrors([
                'email' => 'Nous ne trouvons pas d\'utilisateur avec cette adresse e-mail.',
            ])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json([
                "status" => true,
                "message" => "Mot de passe réinitialisé avec succès"
            ]);
        }
        
        // Pour les requêtes web, rediriger vers la page de connexion
        return redirect()->route('login')->with('status', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
    }
}
