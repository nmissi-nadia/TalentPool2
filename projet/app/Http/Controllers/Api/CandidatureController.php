<?php

namespace App\Http\Controllers\Api;

use App\Services\CandidatureService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

    // Détermine si la requête est une requête API ou Web
    protected function isApiRequest(Request $request)
    {
        return $request->expectsJson() || $request->is('api/*');
    }

    public function __construct(CandidatureService $service)
    {
        $this->service = $service;
        
        // Les middlewares sont gérés dans les routes pour les requêtes web
    }
    /**
     * @OA\Get(
     *     path="/api/candidatures",
     *     summary="Liste des candidatures",
     *     tags={"Candidatures"},
     *     @OA\Response(response="200", description="Liste des candidatures")
     * )
     */

    public function index(Request $request)
    {
        $candidatures = $this->service->getAll();
        
        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json($candidatures, 200);
        }
        
        // Pour les requêtes web, renvoyer une vue
        return view('candidatures.index', compact('candidatures'));
    }

    /**
     * @OA\Get(
     *     path="/api/candidatures/{id}",
     *     summary="Récupérer une candidature par son ID",
     *     tags={"Candidatures"},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID de la candidature", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Candidature récupérée"),
     *     @OA\Response(response="404", description="Candidature non trouvée")
     * )
     */
    public function show(Request $request, int $id)
    {
        try {
            $candidature = $this->service->get($id);
            
            if (!$candidature) {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Candidature non trouvée'], 404);
                }
                return redirect()->route('candidatures.index')->with('error', 'Candidature non trouvée');
            }
            
            // Pour les requêtes API, renvoyer une réponse JSON
            if ($this->isApiRequest($request)) {
                return response()->json($candidature, 200);
            }
            
            // Pour les requêtes web, renvoyer une vue
            return view('candidatures.show', compact('candidature'));
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return redirect()->route('candidatures.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new candidature
     */
    public function create(Request $request)
    {
        // Cette méthode n'est utilisée que pour les requêtes web
        $annonceId = $request->get('annonce_id');
        return view('candidatures.create', compact('annonceId'));
    }

    /**
     * Store a newly created candidature
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'annonce_id' => 'required|exists:annonces,id',
            'cv' => 'required|string',
            'lettre_motivation' => 'required|string',
            'statut' => 'required|in:en attente,acceptée,refusée,en_attente,acceptee,refusee',
        ]);

        try {
            // Ajouter l'ID du candidat à partir de l'utilisateur connecté
            $validated['candidat_id'] = Auth::id();
            
            $candidature = $this->service->create($validated);
            
            // Pour les requêtes API, renvoyer une réponse JSON
            if ($this->isApiRequest($request)) {
                return response()->json($candidature, 201);
            }
            
            // Pour les requêtes web, rediriger vers la page de l'annonce associée
            return redirect()->route('annonces.show', $validated['annonce_id'])
                ->with('success', 'Candidature envoyée avec succès');
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
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
    /**
     * Show the form for editing the specified candidature
     */
    public function edit(Request $request, int $id)
    {
        // Cette méthode n'est utilisée que pour les requêtes web
        try {
            $candidature = $this->service->get($id);
            
            if (!$candidature) {
                return redirect()->route('candidatures.index')->with('error', 'Candidature non trouvée');
            }
            
            // Vérifier que l'utilisateur est bien le propriétaire de la candidature
            if (Auth::id() != $candidature->candidat_id && Auth::user()->role != 'admin') {
                return redirect()->route('candidatures.index')
                    ->with('error', 'Vous n\'êtes pas autorisé à modifier cette candidature');
            }
            
            return view('candidatures.edit', compact('candidature'));
        } catch (\Exception $e) {
            return redirect()->route('candidatures.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified candidature
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'annonce_id' => 'required|exists:annonces,id',
            'cv' => 'required|string',
            'lettre_motivation' => 'required|string',
            'statut' => 'required|in:en attente,acceptée,refusée,en_attente,acceptee,refusee',
        ]);

        try {
            // Vérifier que l'utilisateur est autorisé à modifier cette candidature
            $candidature = $this->service->get($id);
            
            if (!$candidature) {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Candidature non trouvée'], 404);
                }
                return redirect()->route('candidatures.index')->with('error', 'Candidature non trouvée');
            }
            
            // Vérifier que l'utilisateur est le propriétaire de la candidature ou un admin
            if (Auth::id() != $candidature->candidat_id && Auth::user()->role != 'admin') {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Non autorisé'], 403);
                }
                return redirect()->route('candidatures.index')
                    ->with('error', 'Vous n\'êtes pas autorisé à modifier cette candidature');
            }
            
            $candidature = $this->service->update($id, $validated);
            
            // Pour les requêtes API, renvoyer une réponse JSON
            if ($this->isApiRequest($request)) {
                return response()->json($candidature, 200);
            }
            
            // Pour les requêtes web, rediriger vers la liste des candidatures
            return redirect()->route('candidatures.index')
                ->with('success', 'Candidature mise à jour avec succès');
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
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
    public function destroy(Request $request, int $id)
    {
        try {
            // Vérifier que la candidature existe
            $candidature = $this->service->get($id);
            
            if (!$candidature) {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Candidature non trouvée'], 404);
                }
                return redirect()->route('candidatures.index')->with('error', 'Candidature non trouvée');
            }
            
            // Vérifier que l'utilisateur est autorisé à supprimer cette candidature
            if (Auth::user()->role != 'admin' && Auth::id() != $candidature->candidat_id) {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Non autorisé'], 403);
                }
                return redirect()->route('candidatures.index')
                    ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette candidature');
            }
            
            $this->service->delete($id);
            
            // Pour les requêtes API, renvoyer une réponse JSON
            if ($this->isApiRequest($request)) {
                return response()->json(['message' => 'Candidature supprimée avec succès'], 200);
            }
            
            // Pour les requêtes web, rediriger vers la liste des candidatures
            return redirect()->route('candidatures.index')
                ->with('success', 'Candidature supprimée avec succès');
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return redirect()->route('candidatures.index')->with('error', $e->getMessage());
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
    public function getStats(Request $request)
    {
        $stats = $this->service->getStats();
        
        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json($stats, 200);
        }
        
        // Pour les requêtes web, renvoyer une vue
        return view('candidatures.stats', compact('stats'));
    }
    /**
     * @OA\Get(
     *     path="/api/candidatures/annonce/{annonceId}",
     *     summary="Récupérer les candidatures par annonce",
     *     tags={"Candidatures"},
     *     @OA\Parameter(name="annonceId", in="path", required=true, description="ID de l'annonce", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Candidatures récupérées")
     * )
     */
    public function getByAnnonce(Request $request, int $annonceId)
    {
        $candidatures = $this->service->getByAnnonce($annonceId);
        
        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json($candidatures, 200);
        }
        
        // Pour les requêtes web, renvoyer une vue
        return view('candidatures.by-annonce', compact('candidatures', 'annonceId'));
    }
    /**
     * @OA\Get(
     *     path="/api/candidatures/candidat/{candidatId}",
     *     summary="Récupérer les candidatures par candidat",
     *     tags={"Candidatures"},
     *     @OA\Parameter(name="candidatId", in="path", required=true, description="ID du candidat", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Candidatures récupérées")
     * )
     */
    public function getByCandidat(Request $request)
    {
        // Si c'est une requête Web, on utilise l'ID de l'utilisateur connecté
        $candidatId = Auth::id();
        
        // Pour les requêtes API, on peut permettre de spécifier un ID différent si c'est un admin
        if ($this->isApiRequest($request) && $request->has('candidat_id') && Auth::user()->role === 'admin') {
            $candidatId = $request->input('candidat_id');
        }
        
        $candidatures = $this->service->getByCandidat($candidatId);
        
        // Pour les requêtes API, renvoyer une réponse JSON
        if ($this->isApiRequest($request)) {
            return response()->json($candidatures, 200);
        }
        
        // Pour les requêtes web, renvoyer une vue
        return view('candidatures.index', compact('candidatures'));
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
            'statut' => 'required|in:en attente,acceptée,refusée,en_attente,acceptee,refusee',
        ]);

        try {
            // Vérifier que la candidature existe
            $candidature = $this->service->get($id);
            
            if (!$candidature) {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Candidature non trouvée'], 404);
                }
                return redirect()->back()->with('error', 'Candidature non trouvée');
            }
            
            // Vérifier que l'utilisateur est autorisé à modifier le statut
            // Un recruteur peut modifier le statut si l'annonce lui appartient
            $isAuthorized = Auth::user()->role === 'admin' || 
                           (Auth::user()->role === 'recruteur' && 
                            $candidature->annonce && 
                            $candidature->annonce->recruteur_id === Auth::id());
            
            if (!$isAuthorized) {
                if ($this->isApiRequest($request)) {
                    return response()->json(['error' => 'Non autorisé'], 403);
                }
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier le statut de cette candidature');
            }
            
            $candidature = $this->service->update($id, $validated);
            
            // Pour les requêtes API, renvoyer une réponse JSON
            if ($this->isApiRequest($request)) {
                return response()->json($candidature, 200);
            }
            
            // Pour les requêtes web, rediriger vers la liste des candidatures pour cette annonce
            return redirect()->route('candidatures.by-annonce', $candidature->annonce_id)
                ->with('success', 'Statut de la candidature mis à jour avec succès');
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


}