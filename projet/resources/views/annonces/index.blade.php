@extends('layouts.app')

@section('title', 'Annonces')

@section('content')
<div class="row bg-color-red">
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

<!-- Le modal a été supprimé car nous utilisons maintenant des pages séparées pour créer et modifier les annonces -->
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
            console.log('API Response:', data);
            annonces = data;
            
            if (Array.isArray(data) && data.length === 0) {
                document.getElementById('annoncesList').innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-info">
                            Aucune annonce n'est disponible pour le moment.
                        </div>
                    </div>
                `;
                return;
            }
            
            if (!Array.isArray(data)) {
                console.error('Expected an array of annonces, but got:', typeof data);
                document.getElementById('annoncesList').innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger">
                            Format de données incorrect. Veuillez contacter l'administrateur.
                        </div>
                    </div>
                `;
                return;
            }
            
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
                <div class="card h-100 bg-blue">
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
                                <a href="/annonces/edit/${annonce.id}" class="btn btn-outline-secondary btn-sm">Modifier</a>
                                <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="${annonce.id}">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            annoncesList.appendChild(annonceCard);
        });
        
        // Ajouter les événements pour les boutons de suppression
        
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
        window.location.href = '/annonces/create';
    });
    
    
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