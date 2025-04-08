@extends('layouts.app')

@section('title', 'Détail de l\'annonce')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="card-title" id="annonceTitle">Chargement...</h1>
                    <span class="badge" id="annonceStatus"></span>
                </div>
                
                <div class="mb-4">
                    <h5>Description</h5>
                    <p id="annonceDescription">Chargement...</p>
                </div>
                
                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Publiée par:</strong> <span id="annonceRecruteur">Chargement...</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date de publication:</strong> <span id="annonceDate">Chargement...</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger d-none" id="loadError">
                    Une erreur est survenue lors du chargement de l'annonce.
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="/annonces" class="btn btn-secondary">Retour aux annonces</a>
                    
                    <div id="recruteurActions" class="d-none">
                        <button class="btn btn-primary me-2" id="editAnnonceBtn">Modifier</button>
                        <button class="btn btn-danger" id="deleteAnnonceBtn">Supprimer</button>
                    </div>
                    
                    <button class="btn btn-success d-none" id="postulerBtn" data-bs-toggle="modal" data-bs-target="#candidatureModal">
                        Postuler à cette annonce
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Section des candidatures (visible uniquement pour les recruteurs) -->
        <div class="card mt-4 d-none" id="candidaturesSection">
            <div class="card-body">
                <h3>Candidatures reçues</h3>
                
                <div class="alert alert-info d-none" id="noCandidatures">
                    Aucune candidature n'a été reçue pour cette annonce.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped" id="candidaturesTable">
                        <thead>
                            <tr>
                                <th>Candidat</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="candidaturesList">
                            <!-- Les candidatures seront chargées ici dynamiquement -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour postuler -->
<div class="modal fade" id="candidatureModal" tabindex="-1" aria-labelledby="candidatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="candidatureModalLabel">Postuler à l'annonce</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="candidatureForm">
                    <div class="mb-3">
                        <label for="cv" class="form-label">CV</label>
                        <textarea class="form-control" id="cv" rows="5" placeholder="Copiez-collez votre CV ici ou décrivez votre parcours professionnel" required></textarea>
                        <div class="invalid-feedback" id="cvError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lettre_motivation" class="form-label">Lettre de motivation</label>
                        <textarea class="form-control" id="lettre_motivation" rows="5" placeholder="Expliquez pourquoi vous êtes intéressé par ce poste" required></textarea>
                        <div class="invalid-feedback" id="lettreError"></div>
                    </div>
                    
                    <div class="alert alert-danger d-none" id="candidatureError"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="submitCandidatureBtn">Envoyer ma candidature</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails d'une candidature -->
<div class="modal fade" id="viewCandidatureModal" tabindex="-1" aria-labelledby="viewCandidatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCandidatureModalLabel">Détails de la candidature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6>Candidat</h6>
                    <p id="viewCandidatName"></p>
                </div>
                
                <div class="mb-3">
                    <h6>CV</h6>
                    <p id="viewCV"></p>
                </div>
                
                <div class="mb-3">
                    <h6>Lettre de motivation</h6>
                    <p id="viewLettre"></p>
                </div>
                
                <div class="mb-3">
                    <h6>Statut</h6>
                    <div id="viewStatut"></div>
                </div>
                
                <div class="mb-3 d-none" id="statutUpdateSection">
                    <h6>Mettre à jour le statut</h6>
                    <div class="d-flex">
                        <select class="form-select me-2" id="updateStatut">
                            <option value="en_attente">En attente</option>
                            <option value="acceptee">Acceptée</option>
                            <option value="refusee">Refusée</option>
                        </select>
                        <button class="btn btn-primary" id="updateStatutBtn">Mettre à jour</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Variables globales
    let annonceId = null;
    let userRole = null;
    let userId = null;
    let candidatures = [];
    
    // Récupérer l'ID de l'annonce depuis l'URL
    function getAnnonceId() {
        const pathParts = window.location.pathname.split('/');
        return pathParts[pathParts.length - 1];
    }
    
    // Vérifier si l'utilisateur est connecté et récupérer son rôle
    function checkUserRole() {
        const token = localStorage.getItem('token');
        if (token) {
            fetch(`${API_BASE_URL}/profile`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Failed to get user profile');
            })
            .then(data => {
                userRole = data.data.role;
                userId = data.data.id;
                
                // Afficher les boutons selon le rôle
                if (userRole === 'recruteur') {
                    document.getElementById('recruteurActions').classList.remove('d-none');
                    document.getElementById('candidaturesSection').classList.remove('d-none');
                    loadCandidatures();
                } else if (userRole === 'candidat') {
                    document.getElementById('postulerBtn').classList.remove('d-none');
                    checkIfAlreadyApplied();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
    
    // Charger les détails de l'annonce
    function loadAnnonceDetails() {
        annonceId = getAnnonceId();
        
        if (!annonceId) {
            document.getElementById('loadError').classList.remove('d-none');
            document.getElementById('loadError').textContent = 'Annonce non trouvée';
            return;
        }
        
        const token = localStorage.getItem('token');
        const headers = token ? { 'Authorization': `Bearer ${token}` } : {};
        
        fetch(`${API_BASE_URL}/annonces/${annonceId}`, {
            headers: headers
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Failed to load annonce details');
        })
        .then(data => {
            console.log('API Response (annonce details):', data);
            
            // Vérifier si les données sont valides
            if (!data || typeof data !== 'object') {
                throw new Error('Format de données incorrect');
            }
            
            // Afficher les détails de l'annonce
            document.getElementById('annonceTitle').textContent = data.titre || 'Titre non disponible';
            document.getElementById('annonceDescription').textContent = data.description || 'Description non disponible';
            
            // Afficher le statut avec la couleur appropriée
            const statusBadge = document.getElementById('annonceStatus');
            const statut = data.statut || 'inconnue';
            statusBadge.textContent = statut;
            statusBadge.className = `badge ${statut === 'ouverte' ? 'bg-success' : 'bg-secondary'}`;
            
            // Afficher la date de publication
            let dateText = 'Date non disponible';
            if (data.created_at) {
                try {
                    const date = new Date(data.created_at);
                    dateText = date.toLocaleDateString('fr-FR');
                } catch (e) {
                    console.error('Error formatting date:', e);
                }
            }
            document.getElementById('annonceDate').textContent = dateText;
            
            // Afficher le nom du recruteur (si disponible)
            if (data.recruteur && data.recruteur.name) {
                document.getElementById('annonceRecruteur').textContent = data.recruteur.name;
            } else {
                document.getElementById('annonceRecruteur').textContent = 'Recruteur';
                console.log('Recruteur information missing or incomplete:', data.recruteur);
            }
            
            // Désactiver le bouton "Postuler" si l'annonce est fermée
            if (statut === 'fermée') {
                const postulerBtn = document.getElementById('postulerBtn');
                if (postulerBtn) {
                    postulerBtn.disabled = true;
                    postulerBtn.textContent = 'Annonce fermée';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadError').classList.remove('d-none');
        });
    }
    
    // Vérifier si l'utilisateur a déjà postulé à cette annonce
    function checkIfAlreadyApplied() {
        const token = localStorage.getItem('token');
        
        fetch(`${API_BASE_URL}/candidatures/candidat/${userId}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Failed to load candidatures');
        })
        .then(data => {
            // Vérifier si l'utilisateur a déjà postulé à cette annonce
            const alreadyApplied = data.some(candidature => candidature.annonce_id == annonceId);
            
            if (alreadyApplied) {
                const postulerBtn = document.getElementById('postulerBtn');
                postulerBtn.disabled = true;
                postulerBtn.textContent = 'Vous avez déjà postulé';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Charger les candidatures pour cette annonce (pour les recruteurs)
    function loadCandidatures() {
        const token = localStorage.getItem('token');
        
        fetch(`${API_BASE_URL}/candidatures/annonce/${annonceId}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Failed to load candidatures');
        })
        .then(data => {
            candidatures = data;
            
            if (candidatures.length === 0) {
                document.getElementById('noCandidatures').classList.remove('d-none');
                document.getElementById('candidaturesTable').classList.add('d-none');
                return;
            }
            
            document.getElementById('noCandidatures').classList.add('d-none');
            document.getElementById('candidaturesTable').classList.remove('d-none');
            
            const candidaturesList = document.getElementById('candidaturesList');
            candidaturesList.innerHTML = '';
            
            candidatures.forEach(candidature => {
                const date = new Date(candidature.created_at);
                const formattedDate = date.toLocaleDateString('fr-FR');
                
                let statusBadge = '';
                if (candidature.statut === 'en_attente') {
                    statusBadge = '<span class="badge bg-warning">En attente</span>';
                } else if (candidature.statut === 'acceptee') {
                    statusBadge = '<span class="badge bg-success">Acceptée</span>';
                } else if (candidature.statut === 'refusee') {
                    statusBadge = '<span class="badge bg-danger">Refusée</span>';
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${candidature.candidat ? candidature.candidat.name : 'Candidat'}</td>
                    <td>${formattedDate}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-primary view-candidature" data-id="${candidature.id}">Voir</button>
                    </td>
                `;
                
                candidaturesList.appendChild(row);
            });
            
            // Ajouter les événements pour les boutons de visualisation
            document.querySelectorAll('.view-candidature').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    viewCandidature(id);
                });
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Voir les détails d'une candidature
    function viewCandidature(id) {
        const candidature = candidatures.find(c => c.id == id);
        
        if (candidature) {
            document.getElementById('viewCandidatName').textContent = candidature.candidat ? candidature.candidat.name : 'Candidat';
            document.getElementById('viewCV').textContent = candidature.cv;
            document.getElementById('viewLettre').textContent = candidature.lettre_motivation;
            
            let statusBadge = '';
            if (candidature.statut === 'en_attente') {
                statusBadge = '<span class="badge bg-warning">En attente</span>';
            } else if (candidature.statut === 'acceptee') {
                statusBadge = '<span class="badge bg-success">Acceptée</span>';
            } else if (candidature.statut === 'refusee') {
                statusBadge = '<span class="badge bg-danger">Refusée</span>';
            }
            
            document.getElementById('viewStatut').innerHTML = statusBadge;
            
            // Afficher la section de mise à jour du statut
            document.getElementById('statutUpdateSection').classList.remove('d-none');
            document.getElementById('updateStatut').value = candidature.statut;
            
            // Ajouter l'événement pour le bouton de mise à jour du statut
            document.getElementById('updateStatutBtn').onclick = function() {
                updateCandidatureStatus(id, document.getElementById('updateStatut').value);
            };
            
            // Afficher le modal
            const modal = new bootstrap.Modal(document.getElementById('viewCandidatureModal'));
            modal.show();
        }
    }
    
    // Mettre à jour le statut d'une candidature
    function updateCandidatureStatus(id, statut) {
        const token = localStorage.getItem('token');
        
        fetch(`${API_BASE_URL}/candidatures/${id}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ statut })
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Failed to update candidature status');
        })
        .then(data => {
            showNotification('Statut de la candidature mis à jour avec succès');
            
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('viewCandidatureModal'));
            modal.hide();
            
            // Recharger les candidatures
            loadCandidatures();
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Une erreur est survenue lors de la mise à jour du statut', 'error');
        });
    }
    
    // Postuler à l'annonce
    document.getElementById('submitCandidatureBtn').addEventListener('click', function() {
        // Réinitialiser les erreurs
        document.getElementById('cvError').textContent = '';
        document.getElementById('lettreError').textContent = '';
        document.getElementById('candidatureError').classList.add('d-none');
        
        // Récupérer les données du formulaire
        const cv = document.getElementById('cv').value;
        const lettre_motivation = document.getElementById('lettre_motivation').value;
        
        // Valider le formulaire
        let hasError = false;
        
        if (!cv) {
            document.getElementById('cvError').textContent = 'Le CV est requis';
            document.getElementById('cv').classList.add('is-invalid');
            hasError = true;
        }
        
        if (!lettre_motivation) {
            document.getElementById('lettreError').textContent = 'La lettre de motivation est requise';
            document.getElementById('lettre_motivation').classList.add('is-invalid');
            hasError = true;
        }
        
        if (hasError) {
            return;
        }
        
        const token = localStorage.getItem('token');
        
        fetch(`${API_BASE_URL}/candidatures`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                annonce_id: annonceId,
                cv,
                lettre_motivation,
                statut: 'en_attente'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('candidatureError').textContent = data.error;
                document.getElementById('candidatureError').classList.remove('d-none');
                return;
            }
            
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('candidatureModal'));
            modal.hide();
            
            // Afficher un message de succès
            showNotification('Votre candidature a été envoyée avec succès');
            
            // Désactiver le bouton "Postuler"
            const postulerBtn = document.getElementById('postulerBtn');
            postulerBtn.disabled = true;
            postulerBtn.textContent = 'Vous avez déjà postulé';
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('candidatureError').textContent = 'Une erreur est survenue. Veuillez réessayer.';
            document.getElementById('candidatureError').classList.remove('d-none');
        });
    });
    
    // Modifier l'annonce
    document.getElementById('editAnnonceBtn').addEventListener('click', function() {
        window.location.href = `/annonces/edit/${annonceId}`;
    });
    
    // Supprimer l'annonce
    document.getElementById('deleteAnnonceBtn').addEventListener('click', function() {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')) {
            const token = localStorage.getItem('token');
            
            fetch(`${API_BASE_URL}/annonces/${annonceId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Failed to delete annonce');
            })
            .then(data => {
                showNotification('Annonce supprimée avec succès');
                
                // Rediriger vers la liste des annonces
                setTimeout(() => {
                    window.location.href = '/annonces';
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Une erreur est survenue lors de la suppression de l\'annonce', 'error');
            });
        }
    });
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        checkUserRole();
        loadAnnonceDetails();
    });
</script>
@endsection