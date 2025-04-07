<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Supprimer une utilisateur
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            "status" => true,
            "message" => "Utilisateur supprimé avec succès"
        ]);
    }
    //Modifier une utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        return response()->json([
            "status" => true,
            "message" => "Utilisateur modifié avec succès"
        ]);
    }
    // liste des utilisateurs
    public function index()
    {
        $users = User::all();
        return response()->json([
            "status" => true,
            "message" => "Liste des utilisateurs",
            "data" => $users
        ]);
    }
}
