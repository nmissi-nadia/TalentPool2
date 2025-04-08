@extends('layouts.app')

@section('title', isset($annonce) ? 'Modifier une annonce' : 'Créer une annonce')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center">{{ isset($annonce) ? 'Modifier une annonce' : 'Créer une annonce' }}</h2>
            </div>
            <div class="card-body">
                <form id="annonceForm">
                    <input type="hidden" id="annonceId" value="{{ isset($annonce) ? $annonce->id : '' }}">
                    
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
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">{{ isset($annonce) ? 'Mettre à jour' : 'Créer' }}</button>
                        <a href="/annonces" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Variables
    const isEdit = window.location.pathname.includes('/edit/');
    let annonceId = null;
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        // Si c'est une édition, récupérer l'ID de l'annonce depuis l'URL
        if (isEdit) {
            const pathParts = window.location.pathname.split('/');
            annonceId = pathParts[pathParts.length - 1];
            
            // Charger les données de l'annonce
            loadAnnonceData(annonceId);
        }
        
        // Ajouter l'événement de soumission du formulaire
        document.getElementById('annonceForm').addEventListener('submit', saveAnnonce);
    });
    
    // Charger les données de l'annonce pour l'édition
    function loadAnnonceData(id) {
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
            return;
        }
        
        fetch(`${API_BASE_URL}/annonces/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Failed to load annonce data');
        })
        .then(data => {
            // Remplir le formulaire avec les données de l'annonce
            document.getElementById('titre').value = data.titre;
            document.getElementById('description').value = data.description;
            document.getElementById('statut').value = data.statut;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('formError').textContent = 'Erreur lors du chargement des données de l\'annonce';
            document.getElementById('formError').classList.remove('d-none');
        });
    }
    
    // Enregistrer l'annonce (création ou mise à jour)
    function saveAnnonce(e) {
        e.preventDefault();
        
        // Réinitialiser les erreurs
        document.getElementById('titreError').textContent = '';
        document.getElementById('descriptionError').textContent = '';
        document.getElementById('statutError').textContent = '';
        document.getElementById('formError').classList.add('d-none');
        
        // Récupérer les données du formulaire
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
        if (!token) {
            window.location.href = '/login';
            return;
        }
        
        const method = isEdit ? 'PUT' : 'POST';
        const url = isEdit ? `${API_BASE_URL}/annonces/${annonceId}` : `${API_BASE_URL}/annonces`;
        
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
            
            // Rediriger vers la liste des annonces avec un message de succès
            localStorage.setItem('notification', isEdit ? 'Annonce mise à jour avec succès' : 'Annonce créée avec succès');
            window.location.href = '/annonces';
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('formError').textContent = 'Une erreur est survenue. Veuillez réessayer.';
            document.getElementById('formError').classList.remove('d-none');
        });
    }
</script>
@endsection