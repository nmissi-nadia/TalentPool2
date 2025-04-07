<?php

namespace App\Http\Controllers\Api;

use App\Services\CandidatureService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// Ajouter documentaion swagger 

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="TalentPool API",
 *         description="API pour la gestion des annonces et des candidatures",
 *         @OA\Contact(
 *             email="contact@talentpool.com"
 *         )
 *     ),
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST,
 *         description="API Server"
 *     )
 * )
 */
class CandidatureController extends Controller
{
    protected $service;

    public function __construct(CandidatureService $service)
    {
        $this->service = $service;
    }
    /**
     * @OA\Get(
     *     path="/api/candidatures",
     *     summary="Liste des candidatures",
     *     tags={"Candidatures"},
     *     @OA\Response(response="200", description="Liste des candidatures")
     * )
     */

    public function index()
    {
        return response()->json($this->service->getAll(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'annonce_id' => 'required|exists:annonces,id',
            'cv' => 'required|string',
            'lettre_motivation' => 'required|string',
            'statut' => 'required|in:en_attente,acceptee,refusee',
        ]);

        try {
            $candidature = $this->service->create($validated);
            return response()->json($candidature, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/candidatures/{id}",
     *     summary="Mise à jour d'une candidature",
     *     tags={"Candidatures"},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID de la candidature", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Candidature mise à jour")
     * )
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'annonce_id' => 'required|exists:annonces,id',
            'cv' => 'required|string',
            'lettre_motivation' => 'required|string',
            'statut' => 'required|in:en_attente,acceptee,refusee',
        ]);

        try {
            $candidature = $this->service->update($id, $validated);
            return response()->json($candidature, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/candidatures/{id}",
     *     summary="Suppression d'une candidature",
     *     tags={"Candidatures"},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID de la candidature", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Candidature supprimée")
     * )
     */
    public function destroy(int $id)
    {
        try {
            $candidature = $this->service->delete($id);
            return response()->json($candidature, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/candidatures/stats",
     *     summary="Statistiques des candidatures",
     *     tags={"Candidatures"},
     *     @OA\Response(response="200", description="Statistiques des candidatures")
     * )
     */
    public function getStats()
    {
        return response()->json($this->service->getStats(), 200);
    }
    /**
     * @OA\Get(
     *     path="/api/candidatures/{annonceId}",
     *     summary="Récupérer les candidatures par annonce",
     *     tags={"Candidatures"},
     *     @OA\Parameter(name="annonceId", in="path", required=true, description="ID de l'annonce", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Candidatures récupérées")
     * )
     */
    public function getByAnnonce(int $annonceId)
    {
        return response()->json($this->service->getByAnnonce($annonceId), 200);
    }
    /**
     * @OA\Get(
     *     path="/api/candidatures/{candidatId}",
     *     summary="Récupérer les candidatures par candidat",
     *     tags={"Candidatures"},
     *     @OA\Parameter(name="candidatId", in="path", required=true, description="ID du candidat", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Candidatures récupérées")
     * )
     */
    public function getByCandidat(int $candidatId)
    {
        return response()->json($this->service->getByCandidat($candidatId), 200);
    }

    /**
     * @OA\Put(
     *     path="/api/candidatures/{id}/status",
     *     summary="Mise à jour du statut d'une candidature",
     *     tags={"Candidatures"},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID de la candidature", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Statut de la candidature mis à jour")
     * )
     */
    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_attente,acceptee,refusee',
        ]);

        try {
            $candidature = $this->service->update($id, $validated);
            return response()->json($candidature, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}