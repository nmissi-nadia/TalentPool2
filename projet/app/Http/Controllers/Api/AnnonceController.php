<?php

namespace App\Http\Controllers\Api;

use App\Services\AnnonceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

    // Détermine si la requête est une requête API ou Web
    protected function isApiRequest(Request $request)
    {
        return $request->expectsJson() || $request->is('api/*');
    }

    public function __construct(AnnonceService $service)
    {
        $this->service = $service;
        
        // Les middlewares sont gérés dans les routes pour les requêtes web
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

    public function index(Request $request)
    {
        $annonces = $this->service->getAll();
        
        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json($annonces, 200);
        }
        
        // Pour les requêtes web, renvoyer une vue
        return view('annonces.index', compact('annonces'));
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
            // Ajouter l'ID du recruteur à partir de l'utilisateur connecté
            $validated['recruteur_id'] = Auth::id();
            
            $annonce = $this->service->create($validated);
            
            // Pour les requêtes API, renvoyer une réponse JSON
            if ($this->isApiRequest($request)) {
                return response()->json($annonce, 201);
            }
            
            // Pour les requêtes web, rediriger vers la page de l'annonce
            return redirect()->route('annonces.show', $annonce->id)
                ->with('success', 'Annonce créée avec succès');
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
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
            // Vérifier que l'utilisateur est autorisé à modifier cette annonce
            $annonce = $this->service->get($id);
            
            if (!$annonce) {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Annonce non trouvée'], 404);
                }
                return redirect()->route('annonces.index')->with('error', 'Annonce non trouvée');
            }
            
            // Vérifier que l'utilisateur est le propriétaire de l'annonce ou un admin
            if (Auth::id() != $annonce->recruteur_id && Auth::user()->role != 'admin') {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Non autorisé'], 403);
                }
                return redirect()->route('annonces.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette annonce');
            }
            
            $annonce = $this->service->update($id, $validated);
            
            // Pour les requêtes API, renvoyer une réponse JSON
            if ($this->isApiRequest($request)) {
                return response()->json($annonce, 200);
            }
            
            // Pour les requêtes web, rediriger vers la page de l'annonce
            return redirect()->route('annonces.show', $annonce->id)
                ->with('success', 'Annonce mise à jour avec succès');
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
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
    public function destroy(Request $request, int $id)
    {
        try {
            // Vérifier que l'annonce existe
            $annonce = $this->service->get($id);
            
            if (!$annonce) {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Annonce non trouvée'], 404);
                }
                return redirect()->route('annonces.index')->with('error', 'Annonce non trouvée');
            }
            
            // Vérifier que l'utilisateur est autorisé à supprimer cette annonce
            if (Auth::user()->role != 'admin' && Auth::id() != $annonce->recruteur_id) {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Non autorisé'], 403);
                }
                return redirect()->route('annonces.index')->with('error', 'Vous n\'êtes pas autorisé à supprimer cette annonce');
            }
            
            $this->service->delete($id);
            
            // Pour les requêtes API, renvoyer une réponse JSON
            if ($this->isApiRequest($request)) {
                return response()->json(['message' => 'Annonce supprimée avec succès'], 200);
            }
            
            // Pour les requêtes web, rediriger vers la liste des annonces
            return redirect()->route('annonces.index')
                ->with('success', 'Annonce supprimée avec succès');
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return redirect()->route('annonces.index')->with('error', $e->getMessage());
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
    public function getStats(Request $request)
    {
        $stats = $this->service->getStats();
        
        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json($stats, 200);
        }
        
        // Pour les requêtes web, renvoyer une vue
        return view('annonces.stats', compact('stats'));
    }

    public function getByRecruteur(Request $request, int $recruteurId)
    {
        $annonces = $this->service->getByRecruteur($recruteurId);
        
        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json($annonces, 200);
        }
        
        // Pour les requêtes web, renvoyer une vue
        return view('annonces.index', compact('annonces'));
    }

    public function getByStatut(Request $request, string $statut)
    {
        $annonces = $this->service->getByStatut($statut);
        
        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json($annonces, 200);
        }
        
        // Pour les requêtes web, renvoyer une vue
        return view('annonces.index', compact('annonces'));
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
    public function show(Request $request, int $id)
    {
        try {
            $annonce = $this->service->get($id);
            
            if (!$annonce) {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Annonce non trouvée'], 404);
                }
                return redirect()->route('annonces.index')->with('error', 'Annonce non trouvée');
            }
            
            // Charger la relation recruteur
            $annonce->load('recruteur');
            
            // Pour les requêtes API, renvoyer une réponse JSON
            if ($this->isApiRequest($request)) {
                return response()->json($annonce, 200);
            }
            
            // Pour les requêtes web, renvoyer une vue
            return view('annonces.show', compact('annonce'));
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return redirect()->route('annonces.index')->with('error', $e->getMessage());
        }
    }

   
}