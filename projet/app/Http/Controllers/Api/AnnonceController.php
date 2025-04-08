<?php

namespace App\Http\Controllers\Api;

use App\Services\AnnonceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\AnnonceRepositoryInterface;


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
 /**
 * @OA\Schema(
 *     schema="Annonce",
 *     type="object",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="titre", type="string", example="Développeur PHP"),
 *         @OA\Property(property="description", type="string", example="Recherche développeur PHP expérimenté"),
 *         @OA\Property(property="statut", type="string", enum={"ouverte", "fermée"}, example="ouverte"),
 *         @OA\Property(property="recruteur_id", type="integer", example=123),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T10:00:00Z"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-25T15:00:00Z")
 *     }
 * )
 */

class AnnonceController extends Controller
{
    protected $service;

    public function __construct(AnnonceService $service)
    {
        $this->service = $service;
    }
     /**
 * @OA\Get(
 *      path="/annonces",
 *      operationId="getAnnonces",
 *      tags={"Annonces"},
 *      summary="Récupérer toutes les annonces",
 *      description="Retourne la liste de toutes les annonces",
 *      @OA\Response(
 *          response=200,
 *          description="Succès",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(
 *                  type="object",
 *                  @OA\Property(property="id", type="integer"),
 *                  @OA\Property(property="titre", type="string"),
 *                  @OA\Property(property="description", type="string"),
 *                  @OA\Property(property="statut", type="string", enum={"ouverte", "fermée"}),
 *                  @OA\Property(property="recruteur_id", type="integer"),
 *                  @OA\Property(property="created_at", type="string", format="date-time"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time")
 *              )
 *          )
 *      )
 * )
 */

    public function index()
    {
        return response()->json($this->service->getAll(), 200);
    }

    /**
     * @OA\Post(
     *      path="/annonces",
     *      operationId="createAnnonce",
     *      tags={"Annonces"},
     *      summary="Créer une nouvelle annonce",
     *      description="Crée une nouvelle annonce",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     * ref="#/components/schemas/Annonce",
     *              required={"titre", "description", "statut"},
     *              @OA\Property(property="titre", type="string", example="Développeur PHP"),
     *              @OA\Property(property="description", type="string", example="Recherche développeur PHP expérimenté"),
     *              @OA\Property(property="statut", type="string", enum={"ouverte", "fermée"}, example="ouverte")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Annonce créée avec succès",
     *          @OA\JsonContent(ref="#/components/schemas/Annonce")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Les données envoyées ne sont pas valides")
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'statut' => 'required|in:ouverte,fermée',
        ]);

        try {
            $annonce = $this->service->create($validated);
            return response()->json($annonce, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *      path="/annonces/{id}",
     *      operationId="updateAnnonce",
     *      tags={"Annonces"},
     *      summary="Mettre à jour une annonce",
     *      description="Met à jour une annonce existante",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID de l'annonce",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     * ref="#/components/schemas/Annonce",
     *              @OA\Property(property="titre", type="string", example="Développeur PHP"),
     *              @OA\Property(property="description", type="string", example="Recherche développeur PHP expérimenté"),
     *              @OA\Property(property="statut", type="string", enum={"ouverte", "fermée"}, example="ouverte")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Annonce mise à jour avec succès",
     *          @OA\JsonContent(ref="#/components/schemas/Annonce")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Annonce non trouvée"
     *      )
     * )
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'titre' => 'string|max:255',
            'description' => 'string',
            'statut' => 'in:ouverte,fermée',
        ]);

        try {
            $annonce = $this->service->update($id, $validated);
            return response()->json($annonce, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/annonces/{id}",
     *      operationId="deleteAnnonce",
     *      tags={"Annonces"},
     *      summary="Supprimer une annonce",
     *      description="Supprime une annonce existante",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID de l'annonce",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Annonce supprimée avec succès"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Annonce non trouvée"
     *      )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $annonce = $this->service->delete($id);
            return response()->json($annonce, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/annonces/stats",
     *      operationId="getAnnonceStats",
     *      tags={"Annonces"},
     *      summary="Récupérer les statistiques des annonces",
     *      description="Retourne les statistiques des annonces",
     *      @OA\Response(
     *          response=200,
     *          description="Succès",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="total", type="integer"),
     *              @OA\Property(property="ouverte", type="integer"),
     *              @OA\Property(property="fermee", type="integer")
     *          )
     *      )
     * )
     */
    public function getStats()
    {
        return response()->json($this->service->getStats(), 200);
    }

    public function getByRecruteur(int $recruteurId)
    {
        return response()->json($this->service->getByRecruteur($recruteurId), 200);
    }

    public function getByStatut(string $statut)
    {
        return response()->json($this->service->getByStatut($statut), 200);
    }

    /**
     * @OA\Get(
     *      path="/annonces/{id}",
     *      operationId="getAnnonceById",
     *      tags={"Annonces"},
     *      summary="Récupérer une annonce par son ID",
     *      description="Retourne les détails d'une annonce spécifique",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID de l'annonce",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Succès",
     *          @OA\JsonContent(ref="#/components/schemas/Annonce")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Annonce non trouvée"
     *      )
     * )
     */
    public function show(int $id)
    {
        try {
            $annonce = $this->service->get($id);
            
            if (!$annonce) {
                return response()->json(['error' => 'Annonce non trouvée'], 404);
            }
            
            // Charger la relation recruteur
            $annonce->load('recruteur');
            
            return response()->json($annonce, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   
}