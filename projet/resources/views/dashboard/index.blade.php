@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center">
                <h1 class="card-title mb-4">Chargement de votre tableau de bord...</h1>
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-3">Vous allez être redirigé vers votre espace personnel.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Vérifier si l'utilisateur est connecté et récupérer son rôle
    function checkUserRole() {
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
            if (response.status === 401) {
                // Token expired, try to refresh it
                return refreshToken();
            }
            throw new Error('Failed to get user profile');
        })
        .then(data => {
            if (!data || !data.data || !data.data.role) {
                throw new Error('Invalid user data');
            }
            
            const role = data.data.role;
            
            // Rediriger vers le tableau de bord approprié selon le rôle
            if (role === 'candidat') {
                window.location.href = '/dashboard/candidat';
            } else if (role === 'recruteur') {
                window.location.href = '/dashboard/recruteur';
            } else if (role === 'admin') {
                window.location.href = '/dashboard/admin';
            } else {
                // Rôle inconnu, afficher un message d'erreur
                document.querySelector('.card-body').innerHTML = `
                    <div class="alert alert-danger">
                        Rôle non reconnu. Veuillez contacter l'administrateur.
                    </div>
                    <a href="/logout" class="btn btn-primary">Déconnexion</a>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Erreur d'authentification, rediriger vers la page de connexion
            localStorage.removeItem('token');
            
            document.querySelector('.card-body').innerHTML = `
                <div class="alert alert-danger">
                    Votre session a expiré ou vous n'êtes pas connecté.
                </div>
                <a href="/login" class="btn btn-primary">Se connecter</a>
            `;
        });
    }
    
    // Refresh token
    function refreshToken() {
        const token = localStorage.getItem('token');
        if (!token) {
            return Promise.reject('No token found');
        }
        
        return fetch(`${API_BASE_URL}/refresh-token`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Failed to refresh token');
        })
        .then(data => {
            if (data.status && data.token) {
                // Save new token
                localStorage.setItem('token', data.token);
                
                // Retry getting user profile
                return fetch(`${API_BASE_URL}/profile`, {
                    headers: {
                        'Authorization': `Bearer ${data.token}`
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Failed to get user profile after token refresh');
                });
            } else {
                throw new Error('Invalid token refresh response');
            }
        });
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        checkUserRole();
    });
</script>
@endsection