<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TalentPool - @yield('title', 'Accueil')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand {
            font-weight: bold;
            color: #ffffff;
        }
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        .navbar-nav .nav-link:hover {
            color: #ffffff;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">TalentPool</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/annonces">Annonces</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item" id="loginRegisterLinks">
                        <a class="nav-link" href="/login">Connexion</a>
                    </li>
                    <li class="nav-item" id="loginRegisterLinks">
                        <a class="nav-link" href="/register">Inscription</a>
                    </li>
                    <li class="nav-item dropdown d-none" id="userDropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span id="userName">Utilisateur</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/dashboard">Tableau de bord</a></li>
                            <li><a class="dropdown-item" href="/profile">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" id="logoutButton">Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <!-- Content -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container text-center">
            <p>&copy; 2025 TalentPool. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Common JavaScript -->
    <script>
        // API Base URL
        const API_BASE_URL = '/api';
        
        // Check if user is logged in
        function checkAuth() {
            const token = localStorage.getItem('token');
            if (token) {
                // Show user dropdown, hide login/register links
                document.getElementById('userDropdown').classList.remove('d-none');
                document.getElementById('loginRegisterLinks').classList.add('d-none');
                
                // Get user info
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
                    document.getElementById('userName').textContent = data.data.name;
                })
                .catch(error => {
                    console.error('Error:', error);
                    // If there's an error, clear token and reload
                    localStorage.removeItem('token');
                    window.location.reload();
                });
            } else {
                // Show login/register links, hide user dropdown
                document.getElementById('userDropdown').classList.add('d-none');
                document.getElementById('loginRegisterLinks').classList.remove('d-none');
            }
        }
        
        // Logout function
        document.getElementById('logoutButton').addEventListener('click', function(e) {
            e.preventDefault();
            
            const token = localStorage.getItem('token');
            if (token) {
                fetch(`${API_BASE_URL}/logout`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => {
                    localStorage.removeItem('token');
                    window.location.href = '/';
                })
                .catch(error => {
                    console.error('Error:', error);
                    localStorage.removeItem('token');
                    window.location.href = '/';
                });
            }
        });
        
        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';
            notification.style.display = 'block';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
        
        // Check auth on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkAuth();
        });
    </script>
    
    @yield('scripts')
</body>
</html>