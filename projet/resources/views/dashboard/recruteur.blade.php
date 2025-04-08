@extends('layouts.app')

@section('title', 'Tableau de bord - Recruteur')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="card-title">Tableau de bord</h1>
                        <p class="lead">Bienvenue sur votre espace recruteur</p>
                    </div>
                    <a href="/annonces/create" class="btn btn-success">Créer une annonce</a>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 id="totalAnnonces">0</h3>
                                <p>Annonces publiées</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 id="annoncesOuvertes">0</h3>
                                <p>Annonces ouvertes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 id="totalCandidatures">0</h3>
                                <p>Candidatures reçues</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 id="candidaturesEnAttente">0</h3>
                                <p>Candidatures en attente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h2>Mes annonces</h2>
                
                <div class="alert alert-info d-none" id="noAnnonces">
                    Vous n'avez pas encore publié d'annonce. <a href="/annonces/create">Créer une annonce</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped" id="annoncesTable">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Date de publication</th>
                                <th>Statut</th>
                                <th>Candidatures</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="annoncesList">
                            <!-- Les annonces seront chargées ici dynamiquement -->
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Chargement...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h2>Dernières candidatures</h2>
                
                <div class="alert alert-info d-none" id="noCandidatures">
                    Vous n'avez pas encore reçu de candidature.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped" id="candidaturesTable">
                        <thead>
                            <tr>
                                <th>Candidat</th>
                                <th>Annonce</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="candidaturesList">
                            <!-- Les candidatures seront chargées ici dynamiquement -->
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Chargement...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                    <h6>Annonce</h6>
                    <p id="viewAnnonce"></p>
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
                
                <div class="mb-3">
                    <h6>Date de candidature</h6>
                    <p id="viewDate"></p>
                </div>
                
                <div class="mb-3">
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
    let annonces = [];
    let candidatures = [];
    let userId = null;
    
    // Vérifier si l'utilisateur est connecté et récupérer son ID
    function checkUser() {
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
            return;
        }
        
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
            userId = data.data.id;
            
            // Vérifier si l'utilisateur est bien un recruteur
            if (data.data.role !== 'recruteur') {
                window.location.href = '/dashboard';
                return;
            }
            
            loadAnnonces();
            loadAllCandidatures();
        })
        .catch(error => {
            console.error('Error:', error);
            localStorage.removeItem('token');
            window.location.href = '/login';
        });
    }
    
    // Charger les annonces du recruteur
    function loadAnnonces() {
        const token = localStorage.getItem('token');
        
        fetch(`${API_BASE_URL}/annonces`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Failed to load annonces');
        })
        .then(data => {
            // Filtrer les annonces du recruteur
            annonces = data.filter(annonce => annonce.recruteur_id == userId);
            
            // Mettre à jour les statistiques
            updateAnnonceStats();
            
            if (annonces.length === 0) {
                document.getElementById('noAnnonces').classList.remove('d-none');
                document.getElementById('annoncesTable').classList.add('d-none');
                return;
            }
            
            document.getElementById('noAnnonces').classList.add('d-none');
            document.getElementById('annoncesTable').classList.remove('d-none');
            
            const annoncesList = document.getElementById('annoncesList');
            annoncesList.innerHTML = '';
            
            annonces.forEach(annonce => {
                const date = new Date(annonce.created_at);
                const formattedDate = date.toLocaleDateString('fr-FR');
                
                let statusBadge = '';
                if (annonce.statut === 'ouverte') {
                    statusBadge = '<span class="badge bg-success">Ouverte</span>';
                } else if (annonce.statut === 'fermée') {
                    statusBadge = '<span class="badge bg-secondary">Fermée</span>';
                }
                
                // Compter les candidatures pour cette annonce
                const candidaturesCount = candidatures.filter(c => c.annonce_id == annonce.id).length;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${annonce.titre}</td>
                    <td>${formattedDate}</td>
                    <td>${statusBadge}</td>
                    <td>${candidaturesCount}</td>
                    <td>
                        <a href="/annonces/${annonce.id}" class="btn btn-sm btn-primary">Voir</a>
                        <a href="/annonces/edit/${annonce.id}" class="btn btn-sm btn-secondary">Modifier</a>
                        <button class="btn btn-sm btn-danger delete-annonce" data-id="${annonce.id}">Supprimer</button>
                    </td>
                `;
                
                annoncesList.appendChild(row);
            });
            
            // Ajouter les événements pour les boutons de suppression
            document.querySelectorAll('.delete-annonce').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteAnnonce(id);
                });
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('annoncesList').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center">
                        <div class="alert alert-danger">
                            Une erreur est survenue lors du chargement des annonces. Veuillez réessayer.
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    // Charger toutes les candidatures pour les annonces du recruteur
    function loadAllCandidatures() {
        const token = localStorage.getItem('token');
        
        fetch(`${API_BASE_URL}/candidatures`, {
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
            
            // Mettre à jour les statistiques
            updateCandidatureStats();
            
            if (candidatures.length === 0) {
                document.getElementById('noCandidatures').classList.remove('d-none');
                document.getElementById('candidaturesTable').classList.add('d-none');
                return;
            }
            
            document.getElementById('noCandidatures').classList.add('d-none');
            document.getElementById('candidaturesTable').classList.remove('d-none');
            
            const candidaturesList = document.getElementById('candidaturesList');
            candidaturesList.innerHTML = '';
            
            // Trier les candidatures par date (les plus récentes d'abord)
            candidatures.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            
            // Afficher les 10 dernières candidatures
            candidatures.slice(0, 10).forEach(candidature => {
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
                    <td>${candidature.annonce ? candidature.annonce.titre : 'Annonce'}</td>
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
            document.getElementById('candidaturesList').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center">
                        <div class="alert alert-danger">
                            Une erreur est survenue lors du chargement des candidatures. Veuillez réessayer.
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    // Mettre à jour les statistiques des annonces
    function updateAnnonceStats() {
        const total = annonces.length;
        const ouvertes = annonces.filter(a => a.statut === 'ouverte').length;
        
        document.getElementById('totalAnnonces').textContent = total;
        document.getElementById('annoncesOuvertes').textContent = ouvertes;
    }
    
    // Mettre à jour les statistiques des candidatures
    function updateCandidatureStats() {
        const total = candidatures.length;
        const enAttente = candidatures.filter(c => c.statut === 'en_attente').length;
        
        document.getElementById('totalCandidatures').textContent = total;
        document.getElementById('candidaturesEnAttente').textContent = enAttente;
    }
    
    // Supprimer une annonce
    function deleteAnnonce(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')) {
            const token = localStorage.getItem('token');
            
            fetch(`${API_BASE_URL}/annonces/${id}`, {
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
                
                // Recharger les annonces et les candidatures
                loadAnnonces();
                loadAllCandidatures();
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Une erreur est survenue lors de la suppression de l\'annonce', 'error');
            });
        }
    }
    
    // Voir les détails d'une candidature
    function viewCandidature(id) {
        const candidature = candidatures.find(c => c.id == id);
        
        if (candidature) {
            document.getElementById('viewCandidatName').textContent = candidature.candidat ? candidature.candidat.name : 'Candidat';
            document.getElementById('viewAnnonce').textContent = candidature.annonce ? candidature.annonce.titre : 'Annonce';
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
            
            const date = new Date(candidature.created_at);
            document.getElementById('viewDate').textContent = date.toLocaleDateString('fr-FR');
            
            // Préremplir le statut actuel
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
            loadAllCandidatures();
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Une erreur est survenue lors de la mise à jour du statut', 'error');
        });
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        checkUser();
    });
</script>
@endsection