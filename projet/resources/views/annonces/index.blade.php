@extends('layouts.app')

@section('title', 'Annonces')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Annonces disponibles</h1>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Rechercher une annonce...">
                            <button class="btn btn-primary" type="button" id="searchButton">
                                Rechercher
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="all">Tous les statuts</option>
                            <option value="ouverte">Ouvertes</option>
                            <option value="fermée">Fermées</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-success d-none" id="createAnnonceBtn">
                            Créer une annonce
                        </button>
                    </div>
                </div>
                
                <div class="alert alert-info d-none" id="noResults">
                    Aucune annonce ne correspond à votre recherche.
                </div>
                
                <div id="annoncesList" class="row">
                    <!-- Les annonces seront chargées ici dynamiquement -->
                    <div class="col-12 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer/modifier une annonce -->
<div class="modal fade" id="annonceModal" tabindex="-1" aria-labelledby="annonceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="annonceModalLabel">Créer une annonce</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="annonceForm">
                    <input type="hidden" id="annonceId">
                    
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="titre" required>
                        <div class="invalid-feedback" id="titreError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="5" required></textarea>
                        <div class="invalid-feedback" id="descriptionError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select" id="statut" required>
                            <option value="ouverte">Ouverte</option>
                            <option value="fermée">Fermée</option>
                        </select>
                        <div class="invalid-feedback" id="statutError"></div>
                    </div>
                    
                    <div class="alert alert-danger d-none" id="formError"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveAnnonceBtn">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Variables globales
    let annonces = [];
    let userRole = null;
    let currentSearch = '';
    let currentFilter = 'all';
    
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
                
                // Afficher le bouton de création d'annonce si l'utilisateur est un recruteur
                if (userRole === 'recruteur') {
                    document.getElementById('createAnnonceBtn').classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
    
    // Charger les annonces
    function loadAnnonces() {
        const token = localStorage.getItem('token');
        const headers = token ? { 'Authorization': `Bearer ${token}` } : {};
        
        fetch(`${API_BASE_URL}/annonces`, {
            headers: headers
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Failed to load annonces');
        })
        .then(data => {
            annonces = data;
            displayAnnonces();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('annoncesList').innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        Une erreur est survenue lors du chargement des annonces. Veuillez réessayer.
                    </div>
                </div>
            `;
        });
    }
    
    // Afficher les annonces
    function displayAnnonces() {
        const annoncesList = document.getElementById('annoncesList');
        annoncesList.innerHTML = '';
        
        // Filtrer les annonces selon la recherche et le filtre
        let filteredAnnonces = annonces;
        
        if (currentSearch) {
            filteredAnnonces = filteredAnnonces.filter(annonce => 
                annonce.titre.toLowerCase().includes(currentSearch.toLowerCase()) || 
                annonce.description.toLowerCase().includes(currentSearch.toLowerCase())
            );
        }
        
        if (currentFilter !== 'all') {
            filteredAnnonces = filteredAnnonces.filter(annonce => annonce.statut === currentFilter);
        }
        
        // Afficher un message si aucune annonce ne correspond
        if (filteredAnnonces.length === 0) {
            document.getElementById('noResults').classList.remove('d-none');
            return;
        } else {
            document.getElementById('noResults').classList.add('d-none');
        }
        
        // Afficher les annonces
        filteredAnnonces.forEach(annonce => {
            const date = new Date(annonce.created_at);
            const formattedDate = date.toLocaleDateString('fr-FR');
            
            const annonceCard = document.createElement('div');
            annonceCard.className = 'col-md-6 mb-4';
            annonceCard.innerHTML = `
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">${annonce.titre}</h5>
                        <p class="card-text">${annonce.description.substring(0, 150)}${annonce.description.length > 150 ? '...' : ''}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge ${annonce.statut === 'ouverte' ? 'bg-success' : 'bg-secondary'}">${annonce.statut}</span>
                            <small class="text-muted">Publiée le ${formattedDate}</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <a href="/annonces/${annonce.id}" class="btn btn-primary btn-sm">Voir détails</a>
                            <div class="btn-group ${userRole === 'recruteur' ? '' : 'd-none'}">
                                <button type="button" class="btn btn-outline-secondary btn-sm edit-btn" data-id="${annonce.id}">Modifier</button>
                                <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="${annonce.id}">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            annoncesList.appendChild(annonceCard);
        });
        
        // Ajouter les événements pour les boutons d'édition et de suppression
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editAnnonce(id);
            });
        });
        
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                deleteAnnonce(id);
            });
        });
    }
    
    // Rechercher des annonces
    document.getElementById('searchButton').addEventListener('click', function() {
        currentSearch = document.getElementById('searchInput').value.trim();
        displayAnnonces();
    });
    
    // Filtrer par statut
    document.getElementById('statusFilter').addEventListener('change', function() {
        currentFilter = this.value;
        displayAnnonces();
    });
    
    // Créer une annonce
    document.getElementById('createAnnonceBtn').addEventListener('click', function() {
        // Réinitialiser le formulaire
        document.getElementById('annonceForm').reset();
        document.getElementById('annonceId').value = '';
        document.getElementById('annonceModalLabel').textContent = 'Créer une annonce';
        
        // Afficher le modal
        const modal = new bootstrap.Modal(document.getElementById('annonceModal'));
        modal.show();
    });
    
    // Éditer une annonce
    function editAnnonce(id) {
        const annonce = annonces.find(a => a.id == id);
        
        if (annonce) {
            document.getElementById('annonceId').value = annonce.id;
            document.getElementById('titre').value = annonce.titre;
            document.getElementById('description').value = annonce.description;
            document.getElementById('statut').value = annonce.statut;
            
            document.getElementById('annonceModalLabel').textContent = 'Modifier une annonce';
            
            const modal = new bootstrap.Modal(document.getElementById('annonceModal'));
            modal.show();
        }
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
                loadAnnonces();
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Une erreur est survenue lors de la suppression de l\'annonce', 'error');
            });
        }
    }
    
    // Enregistrer une annonce (création ou modification)
    document.getElementById('saveAnnonceBtn').addEventListener('click', function() {
        // Réinitialiser les erreurs
        document.getElementById('titreError').textContent = '';
        document.getElementById('descriptionError').textContent = '';
        document.getElementById('statutError').textContent = '';
        document.getElementById('formError').classList.add('d-none');
        
        // Récupérer les données du formulaire
        const id = document.getElementById('annonceId').value;
        const titre = document.getElementById('titre').value;
        const description = document.getElementById('description').value;
        const statut = document.getElementById('statut').value;
        
        // Valider le formulaire
        let hasError = false;
        
        if (!titre) {
            document.getElementById('titreError').textContent = 'Le titre est requis';
            document.getElementById('titre').classList.add('is-invalid');
            hasError = true;
        }
        
        if (!description) {
            document.getElementById('descriptionError').textContent = 'La description est requise';
            document.getElementById('description').classList.add('is-invalid');
            hasError = true;
        }
        
        if (!statut) {
            document.getElementById('statutError').textContent = 'Le statut est requis';
            document.getElementById('statut').classList.add('is-invalid');
            hasError = true;
        }
        
        if (hasError) {
            return;
        }
        
        const token = localStorage.getItem('token');
        const method = id ? 'PUT' : 'POST';
        const url = id ? `${API_BASE_URL}/annonces/${id}` : `${API_BASE_URL}/annonces`;
        
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ titre, description, statut })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('formError').textContent = data.error;
                document.getElementById('formError').classList.remove('d-none');
                return;
            }
            
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('annonceModal'));
            modal.hide();
            
            // Afficher un message de succès
            showNotification(id ? 'Annonce modifiée avec succès' : 'Annonce créée avec succès');
            
            // Recharger les annonces
            loadAnnonces();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('formError').textContent = 'Une erreur est survenue. Veuillez réessayer.';
            document.getElementById('formError').classList.remove('d-none');
        });
    });
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        checkUserRole();
        loadAnnonces();
        
        // Recherche en appuyant sur Entrée
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchButton').click();
            }
        });
    });
</script>
@endsection