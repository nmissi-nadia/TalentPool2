<?php

namespace App\Repositories;

use App\Interfaces\CandidatureRepositoryInterface;
use App\Models\Candidature;
use Illuminate\Support\Facades\DB;

class CandidatureRepository implements CandidatureRepositoryInterface
{
    public function index()
    {
        return Candidature::all();
    }

    public function store($data)
    {
        return Candidature::create($data);
    }

    public function update($data, $id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->update($data);
        return $candidature;
    }

    public function show($id)
    {
        return Candidature::findOrFail($id);
    }

    public function destroy($id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->delete();
        return true;
    }

    public function stats()
    {
        $stats = Candidature::select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->get();

        $total = Candidature::count();

        return [
            'statistiques par statut' => $stats,
            'total candidatures' => $total
        ];
    }

    public function getByAnnonce(int $annonceId)
    {
        return Candidature::where('annonce_id', $annonceId)->get();
    }

    public function getByCandidat(int $candidatId)
    {
        return Candidature::where('candidat_id', $candidatId)->get();
    }

    public function getByAnnonceAndCandidat(int $annonceId, int $candidatId)
    {
        return Candidature::where('annonce_id', $annonceId)
            ->where('candidat_id', $candidatId)
            ->first();
    }
}