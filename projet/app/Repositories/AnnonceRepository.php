<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Interfaces\AnnonceRepositoryInterface;
use App\Models\Annonce;

class AnnonceRepository implements AnnonceRepositoryInterface
{
    public function index()
    {
        return Annonce::all();
    }

    public function store($data)
    {
        return Annonce::create($data);
    }

    public function update($data, $id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->update($data);
        return $annonce;
    }

    public function show($id)
    {
        return Annonce::findOrFail($id);
    }

    public function destroy($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->delete();
        return true;
    }

    public function stats()
    {
        $stats = Annonce::select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->get();

        $total = Annonce::count();

        return [
            'statistiques par statut' => $stats,
            'total annonces' => $total
        ];
    }

    public function getByRecruteur(int $recruteurId)
    {
        return Annonce::where('recruteur_id', $recruteurId)->get();
    }

    public function getByStatut(string $statut)
    {
        return Annonce::where('statut', $statut)->get();
    }
}