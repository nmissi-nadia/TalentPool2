@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="dashboard-loading-container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card dashboard-loading-card">
                <div class="card-body text-center p-5">
                    <div class="loading-icon mb-4">
                        <svg class="loading-circle" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="45" />
                        </svg>
                        <i class="fas fa-users-cog loading-logo"></i>
                    </div>
                    
                    <h2 class="card-title mb-3">Préparation de votre espace</h2>
                    <p class="text-muted mb-4">Nous récupérons vos informations personnalisées...</p>
                    
                    <div class="progress-container">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="loading-status mt-2">Chargement de votre profil...</div>
                    </div>
                </div>
            </div>
            
            <!-- Message d'erreur (initialement caché) -->
            <div class="card error-card mt-3 d-none">
                <div class="card-body text-center p-4">
                    <div class="error-message-container">
                        <i class="fas fa-exclamation-triangle text-danger mb-3"></i>
                        <div class="error-message">Une erreur est survenue</div>
                        <div class="error-actions mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .dashboard-loading-container {
        min-height: 70vh;
        display: flex;
        align-items: center;
    }
    
    .dashboard-loading-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .loading-icon {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
    }
    
    .loading-circle {
        width: 100px;
        height: 100px;
        animation: rotate 2s linear infinite;
        transform-origin: center center;
        position: absolute;
        top: 0;
        left: 0;
    }
    
    .loading-circle circle {
        stroke-dasharray: 280;
        stroke-dashoffset: 90;
        stroke-width: 4;
        stroke-miterlimit: 10;
        stroke: var(--primary-color);
        fill: none;
        animation: dash 1.5s ease-in-out infinite;
    }
    
    .loading-logo {
        position: absolute;
        font-size: 36px;
        color: var(--primary-color);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .progress-container {
        max-width: 80%;
        margin: 0 auto;
    }
    
    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
        overflow: hidden;
    }
    
    .progress-bar {
        background-color: var(--primary-color);
        width: 100%;
    }
    
    .loading-status {
        font-size: 14px;
        color: var(--light-text);
    }
    
    .error-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.1);
        background-color: #fff9fa;
        border-left: 4px solid var(--error-color);
    }
    
    .error-message-container i {
        font-size: 40px;
        display: block;
    }
    
    .error-message {
        font-size: 18px;
        font-weight: 500;
        color: var(--error-color);
    }
    
    @keyframes rotate {
        100% {
            transform: rotate(360deg);
        }
    }
    
    @keyframes dash {
        0% {
            stroke-dashoffset: 280;
        }
        50% {
            stroke-dashoffset: 75;
        }
        100% {
            stroke-dashoffset: 280;
        }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    .btn-action {
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        margin: 0 5px;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-danger {
        color: var(--error-color);
        border-color: var(--error-color);
    }
    
    .btn-primary:hover, 
    .btn-outline-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('scripts')
<script>
    // Messages d'état personnalisés
    const loadingMessages = [
        "Chargement de votre profil...",
        "Récupération de vos données...",
        "Préparation de votre tableau de bord...",
        "Presque prêt...",
        "Finalisation..."
    ];
    
    // Variables pour l'animation de chargement
    let messageIndex = 0;
    let messageInterval;
    
    // Démarre l'animation de messages
    function startLoadingAnimation() {
        messageInterval = setInterval(() => {
            document.querySelector('.loading-status').textContent = loadingMessages[messageIndex];
            messageIndex = (messageIndex + 1) % loadingMessages.length;
        }, 1500);
    }
    
    // Arrête l'animation de messages
    function stopLoadingAnimation() {
        clearInterval(messageInterval);
    }
    
    // Affiche un message d'erreur
    function showError(message, isAuthError = false) {
        // Arrêter l'animation de chargement
        stopLoadingAnimation();
        
        // Masquer la carte de chargement avec une animation
        const loadingCard = document.querySelector('.dashboard-loading-card');
        loadingCard.style.opacity = '0';
        
        setTimeout(() => {
            loadingCard.classList.add('d-none');
            
            // Afficher la carte d'erreur
            const errorCard = document.querySelector('.error-card');
            errorCard.classList.remove('d-none');
            errorCard.classList.add('fade-in');
            
            // Mettre à jour le message d'erreur
            document.querySelector('.error-message').textContent = message;
            
            // Ajouter les boutons d'action appropriés
            const actionsContainer = document.querySelector('.error-actions');
            
            if (isAuthError) {
                actionsContainer.innerHTML = `
                    <a href="/login" class="btn btn-primary btn-action">
                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                    </a>
                `;
            } else {
                actionsContainer.innerHTML = `
                    <button onclick="checkUserRole()" class="btn btn-primary btn-action">
                        <i class="fas fa-sync-alt me-2"></i>Réessayer
                    </button>
                    <a href="/" class="btn btn-outline-danger btn-action">
                        <i class="fas fa-home me-2"></i>Accueil
                    </a>
                `;
            }
        }, 300);
    }
    
    // Vérifier si l'utilisateur est connecté et récupérer son rôle
    function checkUserRole() {
        // Masquer la carte d'erreur si elle est visible
        const errorCard = document.querySelector('.error-card');
        if (!errorCard.classList.contains('d-none')) {
            errorCard.classList.add('d-none');
            
            // Afficher à nouveau la carte de chargement
            const loadingCard = document.querySelector('.dashboard-loading-card');
            loadingCard.classList.remove('d-none');
            loadingCard.style.opacity = '1';
            
            // Redémarrer l'animation
            messageIndex = 0;
            startLoadingAnimation();
        }
        
        const token = localStorage.getItem('token');
        if (!token) {
            showError('Vous n\'êtes pas connecté ou votre session a expiré.', true);
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
            
            // Mettre à jour le statut
            document.querySelector('.loading-status').textContent = "Redirection en cours...";
            
            // Rediriger vers le tableau de bord approprié selon le rôle
            setTimeout(() => {
                if (role === 'candidat') {
                    window.location.href = '/dashboard/candidat';
                } else if (role === 'recruteur') {
                    window.location.href = '/dashboard/recruteur';
                } else if (role === 'admin') {
                    window.location.href = '/dashboard/admin';
                } else {
                    // Rôle inconnu, afficher un message d'erreur
                    showError('Rôle non reconnu. Veuillez contacter l\'administrateur.');
                }
            }, 800);
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Erreur d'authentification, rediriger vers la page de connexion
            localStorage.removeItem('token');
            
            showError('Votre session a expiré ou vous n\'êtes pas connecté.', true);
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
        startLoadingAnimation();
        checkUserRole();
    });
</script>
@endsection