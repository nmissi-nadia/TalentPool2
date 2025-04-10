@extends('layouts.app')

@section('title', 'Tableau de bord - Recruteur')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="card-title fw-bold">Tableau de bord</h1>
                            <p class="text-muted mb-0">Bienvenue sur votre espace recruteur</p>
                        </div>
                        <a href="/annonces/create" class="btn btn-success px-4 rounded-pill">
                            <i class="bi bi-plus-circle me-2"></i>Créer une annonce
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards Section -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100 rounded-3 bg-gradient">
                <div class="card-body text-center p-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                    </div>
                    <h3 id="totalAnnonces" class="fw-bold mb-0">0</h3>
                    <p class="text-muted">Annonces publiées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100 rounded-3 bg-gradient">
                <div class="card-body text-center p-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-door-open fs-1 text-success"></i>
                    </div>
                    <h3 id="annoncesOuvertes" class="fw-bold mb-0">0</h3>
                    <p class="text-muted">Annonces ouvertes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100 rounded-3 bg-gradient">
                <div class="card-body text-center p-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-inbox fs-1 text-info"></i>
                    </div>
                    <h3 id="totalCandidatures" class="fw-bold mb-0">0</h3>
                    <p class="text-muted">Candidatures reçues</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 rounded-3 bg-gradient">
                <div class="card-body text-center p-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                    </div>
                    <h3 id="candidaturesEnAttente" class="fw-bold mb-0">0</h3>
                    <p class="text-muted">Candidatures en attente</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Annonces Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fs-4 fw-bold mb-0">Mes annonces</h2>
                        <div class="input-group w-auto">
                            <input type="text" class="form-control" placeholder="Rechercher..." id="searchAnnonces">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info d-none" id="noAnnonces">
                        <i class="bi bi-info-circle me-2"></i>Vous n'avez pas encore publié d'annonce. 
                        <a href="/annonces/create" class="alert-link">Créer une annonce</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="annoncesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Titre</th>
                                    <th>Date de publication</th>
                                    <th>Statut</th>
                                    <th>Candidatures</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="annoncesList">
                                <!-- Les annonces seront chargées ici dynamiquement -->
                                <tr>
                                    <td colspan="5" class="text-center py-4">
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
    
    <!-- Candidatures Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fs-4 fw-bold mb-0">Dernières candidatures</h2>
                        <a href="/candidatures" class="btn btn-outline-primary rounded-pill btn-sm">
                            Voir toutes les candidatures
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info d-none" id="noCandidatures">
                        <i class="bi bi-info-circle me-2"></i>Vous n'avez pas encore reçu de candidature.
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="candidaturesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Candidat</th>
                                    <th>Annonce</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="candidaturesList">
                                <!-- Les candidatures seront chargées ici dynamiquement -->
                                <tr>
                                    <td colspan="5" class="text-center py-4">
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
</div>

<!-- Modal pour voir les détails d'une candidature -->
<div class="modal fade" id="viewCandidatureModal" tabindex="-1" aria-labelledby="viewCandidatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="viewCandidatureModalLabel">Détails de la candidature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body">
                                <h6 class="fw-bold text-muted mb-3">Informations candidat</h6>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-placeholder bg-primary rounded-circle text-white me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0" id="viewCandidatName"></h5>
                                        <small class="text-muted" id="viewCandidatEmail">candidat@example.com</small>
                                    </div>
                                </div>
                                <p class="mb-2"><strong>Postuler pour:</strong> <span id="viewAnnonce"></span></p>
                                <p class="mb-0"><strong>Date de candidature:</strong> <span id="viewDate"></span></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body">
                                <h6 class="fw-bold text-muted mb-3">Statut de la candidature</h6>
                                <div class="mb-3" id="viewStatut"></div>
                                
                                <h6 class="fw-bold">Mettre à jour le statut</h6>
                                <div class="d-flex">
                                    <select class="form-select me-2" id="updateStatut">
                                        <option value="en_attente">En attente</option>
                                        <option value="acceptee">Acceptée</option>
                                        <option value="refusee">Refusée</option>
                                    </select>
                                    <button class="btn btn-primary" id="updateStatutBtn">
                                        <i class="bi bi-check-circle me-1"></i>Mettre à jour
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <ul class="nav nav-tabs mt-3" id="candidatureTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="cv-tab" data-bs-toggle="tab" data-bs-target="#cv" type="button" role="tab" aria-controls="cv" aria-selected="true">CV</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lettre-tab" data-bs-toggle="tab" data-bs-target="#lettre" type="button" role="tab" aria-controls="lettre" aria-selected="false">Lettre de motivation</button>
                    </li>
                </ul>
                <div class="tab-content p-3 border border-top-0 rounded-bottom mb-3" id="candidatureTabsContent">
                    <div class="tab-pane fade show active" id="cv" role="tabpanel" aria-labelledby="cv-tab">
                        <div id="viewCV"></div>
                    </div>
                    <div class="tab-pane fade" id="lettre" role="tabpanel" aria-labelledby="lettre-tab">
                        <div id="viewLettre"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="contactCandidat">
                    <i class="bi bi-envelope me-1"></i>Contacter le candidat
                </button>
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
                    statusBadge = '<span class="badge bg-success rounded-pill px-3">Ouverte</span>';
                } else if (annonce.statut === 'fermée') {
                    statusBadge = '<span class="badge bg-secondary rounded-pill px-3">Fermée</span>';
                }
                
                // Compter les candidatures pour cette annonce
                const candidaturesCount = candidatures.filter(c => c.annonce_id == annonce.id).length;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="fw-semibold">${annonce.titre}</div>
                        <small class="text-muted">${annonce.type_contrat || 'N/A'}</small>
                    </td>
                    <td>${formattedDate}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <span class="badge bg-info rounded-pill">${candidaturesCount}</span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group">
                            <a href="/annonces/${annonce.id}" class="btn btn-sm btn-outline-primary" title="Voir l'annonce">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/annonces/edit/${annonce.id}" class="btn btn-sm btn-outline-secondary" title="Modifier l'annonce">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger delete-annonce" data-id="${annonce.id}" title="Supprimer l'annonce">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
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
                            <i class="bi bi-exclamation-triangle me-2"></i>
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
        
        fetch(`/candidatures`, {
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
                    statusBadge = '<span class="badge bg-warning rounded-pill px-3">En attente</span>';
                } else if (candidature.statut === 'acceptee') {
                    statusBadge = '<span class="badge bg-success rounded-pill px-3">Acceptée</span>';
                } else if (candidature.statut === 'refusee') {
                    statusBadge = '<span class="badge bg-danger rounded-pill px-3">Refusée</span>';
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                <span>${candidature.candidat ? candidature.candidat.name.charAt(0).toUpperCase() : 'C'}</span>
                            </div>
                            <div>
                                ${candidature.candidat ? candidature.candidat.name : 'Candidat'}
                            </div>
                        </div>
                    </td>
                    <td>${candidature.annonce ? candidature.annonce.titre : 'Annonce'}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-date me-2"></i>
                            ${formattedDate}
                        </div>
                    </td>
                    <td>${statusBadge}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-primary rounded-pill view-candidature" data-id="${candidature.id}">
                            <i class="bi bi-eye me-1"></i>Voir
                        </button>
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
                            <i class="bi bi-exclamation-triangle me-2"></i>
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
                // Afficher une notification toast
                const toast = `
                    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">
                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header bg-success text-white">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong class="me-auto">Succès</strong>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                Annonce supprimée avec succès
                            </div>
                        </div>
                    </div>
                `;
                
                const toastContainer = document.createElement('div');
                toastContainer.innerHTML = toast;
                document.body.appendChild(toastContainer);
                
                // Recharger les annonces et les candidatures
                loadAnnonces();
                loadAllCandidatures();
                
                // Fermer le toast après 3 secondes
                setTimeout(() => {
                    const toastEl = document.querySelector('.toast');
                    if (toastEl) {
                        const bsToast = new bootstrap.Toast(toastEl);
                        bsToast.hide();
                    }
                }, 3000);
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
            document.getElementById('viewCandidatEmail').textContent = candidature.candidat ? candidature.candidat.email : 'email@example.com';
            document.getElementById('viewAnnonce').textContent = candidature.annonce ? candidature.annonce.titre : 'Annonce';
            document.getElementById('viewCV').innerHTML = formatTextWithLineBreaks(candidature.cv);
            document.getElementById('viewLettre').innerHTML = formatTextWithLineBreaks(candidature.lettre_motivation);
            
            let statusBadge = '';
            if (candidature.statut === 'en_attente') {
                statusBadge = '<div class="d-flex align-items-center"><div class="p-2 bg-warning rounded-circle me-2"></div><span class="badge bg-warning text-dark px-3 py-2 rounded-pill">En attente</span></div>';
            } else if (candidature.statut === 'acceptee') {
                statusBadge = '<div class="d-flex align-items-center"><div class="p-2 bg-success rounded-circle me-2"></div><span class="badge bg-success px-3 py-2 rounded-pill">Acceptée</span></div>';
            } else if (candidature.statut === 'refusee') {
                statusBadge = '<div class="d-flex align-items-center"><div class="p-2 bg-danger rounded-circle me-2"></div><span class="badge bg-danger px-3 py-2 rounded-pill">Refusée</span></div>';
            }
            
            document.getElementById('viewStatut').innerHTML = statusBadge;
            
            const date = new Date(candidature.created_at);
            document.getElementById('viewDate').textContent = date.toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' });
            
            // Préremplir le statut actuel
            document.getElementById('updateStatut').value = candidature.statut;
            
            // Ajouter l'événement pour le bouton de mise à jour du statut
            document.getElementById('updateStatutBtn').onclick = function() {
                updateCandidatureStatus(id, document.getElementById('updateStatut').value);
            };
            
            // Ajouter l'événement pour le bouton de contact
            document.getElementById('contactCandidat').onclick = function() {
                const email = candidature.candidat ? candidature.candidat.email : '';
                if (email) {
                    window.location.href = `mailto:${email}?subject=Votre candidature pour ${candidature.annonce ? candidature.annonce.titre : 'notre offre'}`;
                } else {
                    alert('L\'adresse email du candidat n\'est pas disponible.');
                }
            };
            
            // Afficher le modal
            const modal = new bootstrap.Modal(document.getElementById('viewCandidatureModal'));
            modal.show();
        }
    }
    
    // Formater le texte avec des sauts de ligne
    function formatTextWithLineBreaks(text) {
        return text ? text.replace(/\n/g, '<br>') : '';
    }
    
    // Mettre à jour le statut d'une candidature
    function updateCandidatureStatus(id, statut) {
        const token = localStorage.getItem('token');
        
        // Montrer un indicateur de chargement
        document.getElementById('updateStatutBtn').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mise à jour...';
        document.getElementById('updateStatutBtn').disabled = true;
        
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
            // Afficher une notification toast
            const toast = `
                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header bg-success text-white">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong class="me-auto">Succès</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            Statut de la candidature mis à jour avec succès
                        </div>
                    </div>
                </div>
            `;
            
            const toastContainer = document.createElement('div');
            toastContainer.innerHTML = toast;
            document.body.appendChild(toastContainer);
            
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('viewCandidatureModal'));
            modal.hide();
            
            // Recharger les candidatures
            loadAllCandidatures();
            
            // Fermer le toast après 3 secondes
            setTimeout(() => {
                const toastEl = document.querySelector('.toast');
                if (toastEl) {
                    const bsToast = new bootstrap.Toast(toastEl);
                    bsToast.hide();
                }
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            // Réinitialiser le bouton
            document.getElementById('updateStatutBtn').innerHTML = '<i class="bi bi-check-circle me-1"></i>Mettre à jour';
            document.getElementById('updateStatutBtn').disabled = false;
            
            // Afficher une notification d'erreur
            const toast = `
                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header bg-danger text-white">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong class="me-auto">Erreur</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            Une erreur est survenue lors de la mise à jour du statut
                        </div>
                    </div>
                </div>
            `;
            
            const toastContainer = document.createElement('div');
            toastContainer.innerHTML = toast;
            document.body.appendChild(toastContainer);
            
            // Fermer le toast après 3 secondes
            setTimeout(() => {
                const toastEl = document.querySelector('.toast');
                if (toastEl) {
                    const bsToast = new bootstrap.Toast(toastEl);
                    bsToast.hide();
                }
            }, 3000);
        });
    }
    
    // Fonction pour créer une notification toast
    function showNotification(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-success' : 'bg-danger';
        const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
        const title = type === 'success' ? 'Succès' : 'Erreur';
        
        const toast = `
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header ${bgColor} text-white">
                        <i class="bi ${icon} me-2"></i>
                        <strong class="me-auto">${title}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            </div>
        `;
        
        const toastContainer = document.createElement('div');
        toastContainer.innerHTML = toast;
        document.body.appendChild(toastContainer);
        
        // Fermer le toast après 3 secondes
        setTimeout(() => {
            const toastEl = document.querySelector('.toast');
            if (toastEl) {
                const bsToast = new bootstrap.Toast(toastEl);
                bsToast.hide();
            }
        }, 3000);
    }
    
    // Recherche d'annonces
    document.getElementById('searchAnnonces').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        const annoncesList = document.getElementById('annoncesList');
        const rows = annoncesList.querySelectorAll('tr');
        
        rows.forEach(row => {
            const titre = row.querySelector('td:first-child')?.textContent.toLowerCase() || '';
            if (titre.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Animation des cartes statistiques
    function animateCounters() {
        const counters = document.querySelectorAll('#totalAnnonces, #annoncesOuvertes, #totalCandidatures, #candidaturesEnAttente');
        counters.forEach(counter => {
            const target = parseInt(counter.innerText);
            let count = 0;
            const duration = 1000; // ms
            const increment = Math.ceil(target / (duration / 50));
            
            const timer = setInterval(() => {
                count += increment;
                if (count >= target) {
                    counter.innerText = target;
                    clearInterval(timer);
                } else {
                    counter.innerText = count;
                }
            }, 50);
        });
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        checkUser();
        
        // Ajouter les tooltips Bootstrap pour les boutons d'action
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Animer les compteurs au chargement de la page
        setTimeout(animateCounters, 500);
    });
</script>
@endsection