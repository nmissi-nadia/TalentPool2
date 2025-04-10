@extends('layouts.app')

@section('title', 'Tableau de bord - Candidat')

@section('content')
<div class="container py-4">
    <!-- En-tête avec bienvenue et statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="card-title fw-bold text-primary mb-1">Tableau de bord</h1>
                            <p class="text-muted mb-0">Bienvenue sur votre espace candidat</p>
                        </div>
                        <a href="/annonces" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-search me-2"></i>Parcourir les annonces
                        </a>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light rounded-3 shadow-sm position-relative overflow-hidden">
                                <div class="position-absolute top-0 start-0 w-100 h-1 bg-primary"></div>
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                            <i class="fas fa-paper-plane text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h3 id="totalCandidatures" class="fw-bold mb-0 fs-2">0</h3>
                                            <p class="text-muted mb-0">Candidatures envoyées</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light rounded-3 shadow-sm position-relative overflow-hidden">
                                <div class="position-absolute top-0 start-0 w-100 h-1 bg-warning"></div>
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                            <i class="fas fa-hourglass-half text-warning fs-4"></i>
                                        </div>
                                        <div>
                                            <h3 id="candidaturesEnAttente" class="fw-bold mb-0 fs-2">0</h3>
                                            <p class="text-muted mb-0">En attente</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light rounded-3 shadow-sm position-relative overflow-hidden">
                                <div class="position-absolute top-0 start-0 w-100 h-1 bg-success"></div>
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                            <i class="fas fa-check-circle text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h3 id="candidaturesAcceptees" class="fw-bold mb-0 fs-2">0</h3>
                                            <p class="text-muted mb-0">Acceptées</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Liste des candidatures -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold mb-0">Mes candidatures</h2>
                        <div class="form-group mb-0">
                            <select id="filterStatus" class="form-select">
                                <option value="all">Tous les statuts</option>
                                <option value="en_attente">En attente</option>
                                <option value="acceptee">Acceptées</option>
                                <option value="refusee">Refusées</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="alert alert-info d-flex align-items-center d-none" id="noCandidatures">
                        <i class="fas fa-info-circle me-3 fs-4"></i>
                        <div>
                            <p class="mb-0">Vous n'avez pas encore envoyé de candidature.</p>
                            <a href="/annonces" class="btn btn-sm btn-primary mt-2">Découvrir les offres d'emploi</a>
                        </div>
                    </div>
                    
                    <div class="table-responsive" id="candidaturesTable">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Annonce</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="candidaturesList" class="border-top-0">
                                <!-- Les candidatures seront chargées ici dynamiquement -->
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Chargement...</span>
                                        </div>
                                        <p class="text-muted mt-2 mb-0">Chargement de vos candidatures...</p>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="viewCandidatureModalLabel">Détails de ma candidature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                            <i class="fas fa-briefcase text-primary"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Poste</h6>
                    </div>
                    <p id="viewAnnonce" class="mb-0 ps-4 ms-2"></p>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                            <i class="fas fa-file-alt text-primary"></i>
                        </div>
                        <h6 class="fw-bold mb-0">CV</h6>
                    </div>
                    <div class="ps-4 ms-2 p-3 bg-light rounded">
                        <p id="viewCV" class="mb-0"></p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                            <i class="fas fa-envelope-open-text text-primary"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Lettre de motivation</h6>
                    </div>
                    <div class="ps-4 ms-2 p-3 bg-light rounded">
                        <p id="viewLettre" class="mb-0"></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="fas fa-tag text-primary"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Statut</h6>
                        </div>
                        <div class="ps-4 ms-2" id="viewStatut"></div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="fas fa-calendar-alt text-primary"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Date de candidature</h6>
                        </div>
                        <p id="viewDate" class="mb-0 ps-4 ms-2"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-danger d-none" id="retractBtn">
                    <i class="fas fa-times-circle me-2"></i>Retirer ma candidature
                </button>
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
            
            // Ajouter le nom de l'utilisateur à la page de bienvenue
            const welcomeMessage = document.querySelector('.text-muted.mb-0');
            welcomeMessage.textContent = `Bienvenue ${data.data.prenom} sur votre espace candidat`;
            
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
            
            renderCandidatures(candidatures);
            
            // Ajouter un écouteur d'événements pour le filtre de statut
            document.getElementById('filterStatus').addEventListener('change', function() {
                const status = this.value;
                let filteredCandidatures = candidatures;
                
                if (status !== 'all') {
                    filteredCandidatures = candidatures.filter(c => c.statut === status);
                }
                
                renderCandidatures(filteredCandidatures);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('candidaturesList').innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <div class="alert alert-danger mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Une erreur est survenue lors du chargement des candidatures. Veuillez réessayer.
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    // Afficher les candidatures dans le tableau
    function renderCandidatures(candidaturesToShow) {
        const candidaturesList = document.getElementById('candidaturesList');
        candidaturesList.innerHTML = '';
        
        if (candidaturesToShow.length === 0) {
            candidaturesList.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="fas fa-search fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Aucune candidature ne correspond à ce filtre.</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        candidaturesToShow.forEach(candidature => {
            const date = new Date(candidature.created_at);
            const formattedDate = date.toLocaleDateString('fr-FR', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            let statusBadge = '';
            let statusClass = '';
            if (candidature.statut === 'en_attente') {
                statusBadge = '<span class="badge bg-warning">En attente</span>';
                statusClass = 'border-start border-warning border-4';
            } else if (candidature.statut === 'acceptee') {
                statusBadge = '<span class="badge bg-success">Acceptée</span>';
                statusClass = 'border-start border-success border-4';
            } else if (candidature.statut === 'refusee') {
                statusBadge = '<span class="badge bg-danger">Refusée</span>';
                statusClass = 'border-start border-danger border-4';
            }
            
            const row = document.createElement('tr');
            row.className = statusClass;
            row.innerHTML = `
                <td class="ps-3">
                    <div class="fw-bold">${candidature.annonce ? candidature.annonce.titre : 'Annonce'}</div>
                    <div class="text-muted small">${candidature.annonce ? candidature.annonce.entreprise : 'Entreprise'}</div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="far fa-calendar-alt text-muted me-2"></i>
                        ${formattedDate}
                    </div>
                </td>
                <td>${statusBadge}</td>
                <td class="text-end pe-3">
                    <button class="btn btn-sm btn-primary rounded-pill view-candidature me-1" data-id="${candidature.id}">
                        <i class="fas fa-eye me-1"></i>Détails
                    </button>
                    <a href="/annonces/${candidature.annonce_id}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        <i class="fas fa-external-link-alt me-1"></i>Voir l'annonce
                    </a>
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
    }
    
    // Mettre à jour les statistiques
    function updateStats() {
        const total = candidatures.length;
        const enAttente = candidatures.filter(c => c.statut === 'en_attente').length;
        const acceptees = candidatures.filter(c => c.statut === 'acceptee').length;
        
        // Animation des compteurs
        animateCounter('totalCandidatures', 0, total);
        animateCounter('candidaturesEnAttente', 0, enAttente);
        animateCounter('candidaturesAcceptees', 0, acceptees);
    }
    
    // Animation des compteurs
    function animateCounter(id, start, end) {
        const duration = 1000;
        const element = document.getElementById(id);
        const startTime = performance.now();
        
        function updateCounter(currentTime) {
            const elapsedTime = currentTime - startTime;
            const progress = Math.min(elapsedTime / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            
            element.textContent = value;
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = end;
            }
        }
        
        requestAnimationFrame(updateCounter);
    }
    
    // Voir les détails d'une candidature
    function viewCandidature(id) {
        const candidature = candidatures.find(c => c.id == id);
        
        if (candidature) {
            const annonceTitle = candidature.annonce ? candidature.annonce.titre : 'Annonce';
            const entrepriseNom = candidature.annonce ? candidature.annonce.entreprise : '';
            
            document.getElementById('viewAnnonce').textContent = entrepriseNom ? `${annonceTitle} - ${entrepriseNom}` : annonceTitle;
            document.getElementById('viewCV').textContent = candidature.cv;
            document.getElementById('viewLettre').textContent = candidature.lettre_motivation;
            
            let statusBadge = '';
            if (candidature.statut === 'en_attente') {
                statusBadge = '<span class="badge bg-warning py-2 px-3">En attente</span>';
            } else if (candidature.statut === 'acceptee') {
                statusBadge = '<span class="badge bg-success py-2 px-3">Acceptée</span>';
            } else if (candidature.statut === 'refusee') {
                statusBadge = '<span class="badge bg-danger py-2 px-3">Refusée</span>';
            }
            
            document.getElementById('viewStatut').innerHTML = statusBadge;
            
            const date = new Date(candidature.created_at);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            document.getElementById('viewDate').textContent = date.toLocaleDateString('fr-FR', options);
            
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
        // Créer un modal de confirmation personnalisé
        const confirmModal = document.createElement('div');
        confirmModal.className = 'modal fade';
        confirmModal.id = 'confirmRetractModal';
        confirmModal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle text-danger fs-1"></i>
                            </div>
                            <div>
                                <p>Êtes-vous sûr de vouloir retirer cette candidature ?</p>
                                <p class="mb-0 text-muted">Cette action est irréversible.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-danger" id="confirmRetract">
                            <i class="fas fa-times-circle me-2"></i>Retirer ma candidature
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(confirmModal);
        
        const confirmModalEl = new bootstrap.Modal(document.getElementById('confirmRetractModal'));
        confirmModalEl.show();
        
        document.getElementById('confirmRetract').addEventListener('click', function() {
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
                // Fermer les modals
                confirmModalEl.hide();
                const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewCandidatureModal'));
                viewModal.hide();
                
                // Afficher une notification de succès
                showToast('Succès', 'Votre candidature a été retirée avec succès.', 'success');
                
                // Recharger les candidatures
                loadCandidatures();
                
                // Supprimer le modal de confirmation
                setTimeout(() => {
                    document.getElementById('confirmRetractModal').remove();
                }, 500);
            })
            .catch(error => {
                console.error('Error:', error);
                confirmModalEl.hide();
                showToast('Erreur', 'Une erreur est survenue lors du retrait de la candidature.', 'error');
                
                // Supprimer le modal de confirmation
                setTimeout(() => {
                    document.getElementById('confirmRetractModal').remove();
                }, 500);
            });
        });
    }
    
    // Afficher une notification toast
    function showToast(title, message, type = 'success') {
        const toastContainer = document.createElement('div');
        toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        
        const iconClass = type === 'success' ? 'fas fa-check-circle text-success' : 'fas fa-exclamation-circle text-danger';
        
        toastContainer.innerHTML = `
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="${iconClass} me-2"></i>
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        document.body.appendChild(toastContainer);
        
        // Supprimer la notification après 5 secondes
        setTimeout(() => {
            toastContainer.remove();
        }, 5000);
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        checkUser();
    });
</script>
@endsection