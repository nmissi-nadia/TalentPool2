@extends('layouts.app')

@section('title', 'Tableau de bord - Candidat')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Tableau de bord</h1>
                <p class="lead">Bienvenue sur votre espace candidat</p>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 id="totalCandidatures">0</h3>
                                <p>Candidatures envoyées</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 id="candidaturesEnAttente">0</h3>
                                <p>En attente</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 id="candidaturesAcceptees">0</h3>
                                <p>Acceptées</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h2>Mes candidatures</h2>
                
                <div class="alert alert-info d-none" id="noCandidatures">
                    Vous n'avez pas encore envoyé de candidature. <a href="/annonces">Parcourir les annonces</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped" id="candidaturesTable">
                        <thead>
                            <tr>
                                <th>Annonce</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="candidaturesList">
                            <!-- Les candidatures seront chargées ici dynamiquement -->
                            <tr>
                                <td colspan="4" class="text-center">
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
                <h5 class="modal-title" id="viewCandidatureModalLabel">Détails de ma candidature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-danger d-none" id="retractBtn">Retirer ma candidature</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Variables globales
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
            
            // Vérifier si l'utilisateur est bien un candidat
            if (data.data.role !== 'candidat') {
                window.location.href = '/dashboard';
                return;
            }
            
            loadCandidatures();
        })
        .catch(error => {
            console.error('Error:', error);
            localStorage.removeItem('token');
            window.location.href = '/login';
        });
    }
    
    // Charger les candidatures de l'utilisateur
    function loadCandidatures() {
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
            updateStats();
            
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
                    <td>${candidature.annonce ? candidature.annonce.titre : 'Annonce'}</td>
                    <td>${formattedDate}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-primary view-candidature" data-id="${candidature.id}">Voir</button>
                        <a href="/annonces/${candidature.annonce_id}" class="btn btn-sm btn-secondary">Voir l'annonce</a>
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
                    <td colspan="4" class="text-center">
                        <div class="alert alert-danger">
                            Une erreur est survenue lors du chargement des candidatures. Veuillez réessayer.
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    // Mettre à jour les statistiques
    function updateStats() {
        const total = candidatures.length;
        const enAttente = candidatures.filter(c => c.statut === 'en_attente').length;
        const acceptees = candidatures.filter(c => c.statut === 'acceptee').length;
        
        document.getElementById('totalCandidatures').textContent = total;
        document.getElementById('candidaturesEnAttente').textContent = enAttente;
        document.getElementById('candidaturesAcceptees').textContent = acceptees;
    }
    
    // Voir les détails d'une candidature
    function viewCandidature(id) {
        const candidature = candidatures.find(c => c.id == id);
        
        if (candidature) {
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
            
            // Afficher le bouton de retrait si la candidature est en attente
            const retractBtn = document.getElementById('retractBtn');
            if (candidature.statut === 'en_attente') {
                retractBtn.classList.remove('d-none');
                retractBtn.onclick = function() {
                    retractCandidature(id);
                };
            } else {
                retractBtn.classList.add('d-none');
            }
            
            // Afficher le modal
            const modal = new bootstrap.Modal(document.getElementById('viewCandidatureModal'));
            modal.show();
        }
    }
    
    // Retirer une candidature
    function retractCandidature(id) {
        if (confirm('Êtes-vous sûr de vouloir retirer cette candidature ?')) {
            const token = localStorage.getItem('token');
            
            fetch(`${API_BASE_URL}/candidatures/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Failed to retract candidature');
            })
            .then(data => {
                showNotification('Candidature retirée avec succès');
                
                // Fermer le modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('viewCandidatureModal'));
                modal.hide();
                
                // Recharger les candidatures
                loadCandidatures();
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Une erreur est survenue lors du retrait de la candidature', 'error');
            });
        }
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        checkUser();
    });
</script>
@endsection