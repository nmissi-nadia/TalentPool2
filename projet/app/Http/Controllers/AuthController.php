<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        // Data validation
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        // Attempt to authenticate
        $credentials = $request->only('email', 'password');
        
        if(Auth::attempt($credentials)) {
            // Regenerate session after login
            $request->session()->regenerate();
            
            // Redirect based on role
            $user = Auth::user();
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

        // Authentication failed
        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput($request->except('password'));
    }
    
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        // Validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:8|confirmed",
            "role" => "required|in:candidat,recruteur"
        ]);
        
        // Create user
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => $request->role
        ]);
        
        // Auto-login after registration
        Auth::login($user);
        
        // Redirect based on role
        switch($user->role) {
            case 'recruteur':
                return redirect()->route('dashboard.recruteur');
            case 'candidat':
                return redirect()->route('dashboard.candidat');
            default:
                return redirect()->route('dashboard.index');
        }
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
    
    public function showResetForm()
    {
        return view('auth.password-reset');
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:8|confirmed"
        ]);

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Utilisateur non trouvé',
            ])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Redirect to login with success message
        return redirect()->route('login')->with('status', 'Mot de passe réinitialisé avec succès');
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }
}