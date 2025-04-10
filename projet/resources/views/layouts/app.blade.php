<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TalentPool - @yield('title', 'Accueil')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1e88e5;
            --primary-hover: #1565c0;
            --secondary-color: #f5f7fa;
            --text-color: #333333;
            --light-text: #6c757d;
            --dark-color: #212529;
            --success-color: #28a745;
            --error-color: #dc3545;
            --border-radius: 12px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .navbar-nav .nav-link {
            color: var(--text-color);
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: var(--transition);
            border-radius: 5px;
            margin: 0 3px;
        }

        .navbar-nav .nav-link:hover, 
        .navbar-nav .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(30, 136, 229, 0.1);
        }

        .dropdown-menu {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--box-shadow);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 5px;
            padding: 0.5rem 1rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: rgba(30, 136, 229, 0.1);
            color: var(--primary-color);
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 24px;
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            padding: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .footer {
            background-color: white;
            color: var(--text-color);
            padding: 2rem 0;
            margin-top: 3rem;
            border-top: 1px solid #f0f0f0;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: var(--light-text);
            margin: 0 15px;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .social-icons {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .social-icons a {
            background-color: #f5f5f5;
            color: var(--light-text);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 8px;
            transition: var(--transition);
        }

        .social-icons a:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: var(--success-color);
            color: white;
            border-radius: var(--border-radius);
            display: none;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .page-container {
            min-height: calc(100vh - 180px);
            padding: 2rem 0;
        }

        .section-title {
            position: relative;
            margin-bottom: 2rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .section-title:after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -10px;
            height: 4px;
            width: 60px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        /* Form styles */
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(30, 136, 229, 0.25);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        /* Custom toggle switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary-color);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        /* Badge styles */
        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            border-radius: 30px;
        }

        .badge-primary {
            background-color: rgba(30, 136, 229, 0.15);
            color: var(--primary-color);
        }

        /* Avatar styles */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        /* Custom progress bar */
        .progress {
            height: 10px;
            border-radius: 5px;
            background-color: #e9ecef;
            margin-bottom: 1rem;
        }

        .progress-bar {
            background-color: var(--primary-color);
            border-radius: 5px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-users-cog me-2"></i>TalentPool
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-home me-1"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/annonces"><i class="fas fa-briefcase me-1"></i> Annonces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/entreprises"><i class="fas fa-building me-1"></i> Entreprises</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact"><i class="fas fa-envelope me-1"></i> Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item" id="loginRegisterLinks">
                        <a class="btn btn-outline-primary me-2" href="/login"><i class="fas fa-sign-in-alt me-1"></i> Connexion</a>
                    </li>
                    <li class="nav-item" id="registerLink">
                        <a class="btn btn-primary" href="/register"><i class="fas fa-user-plus me-1"></i> Inscription</a>
                    </li>
                    <li class="nav-item dropdown d-none" id="userDropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="/api/placeholder/40/40" alt="Avatar" class="avatar">
                            <span id="userName">Utilisateur</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-tachometer-alt me-2"></i> Tableau de bord</a></li>
                            <li><a class="dropdown-item" href="/profile"><i class="fas fa-user-circle me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="/messages"><i class="fas fa-envelope me-2"></i> Messages</a></li>
                            <li><a class="dropdown-item" href="/favoris"><i class="fas fa-heart me-2"></i> Favoris</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" id="logoutButton"><i class="fas fa-sign-out-alt me-2"></i> Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <!-- Content -->
    <div class="page-container">
        <div class="container">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3 fw-bold"><i class="fas fa-users-cog me-2"></i>TalentPool</h5>
                    <p class="text-muted">Votre plateforme de recrutement qui connecte les talents aux meilleures opportunités professionnelles.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3 fw-bold">Liens Rapides</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/" class="text-decoration-none text-muted"><i class="fas fa-angle-right me-2"></i>Accueil</a></li>
                        <li class="mb-2"><a href="/annonces" class="text-decoration-none text-muted"><i class="fas fa-angle-right me-2"></i>Annonces</a></li>
                        <li class="mb-2"><a href="/entreprises" class="text-decoration-none text-muted"><i class="fas fa-angle-right me-2"></i>Entreprises</a></li>
                        <li class="mb-2"><a href="/contact" class="text-decoration-none text-muted"><i class="fas fa-angle-right me-2"></i>Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3 fw-bold">Restez Connecté</h5>
                    <div class="social-icons mb-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                    <p class="text-muted mb-1">Inscrivez-vous à notre newsletter :</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Votre email">
                        <button class="btn btn-primary" type="button">S'abonner</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center text-muted">
                <p class="mb-0">&copy; 2025 TalentPool. Tous droits réservés.</p>
            </div>
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
                document.getElementById('registerLink').classList.add('d-none');
                
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
                    if (response.status === 401) {
                        // Token expired, try to refresh it
                        return refreshToken();
                    }
                    throw new Error('Failed to get user profile');
                })
                .then(data => {
                    if (data && data.data && data.data.name) {
                        document.getElementById('userName').textContent = data.data.name;
                    }
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
                document.getElementById('registerLink').classList.remove('d-none');
            }
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
                    showNotification('Déconnexion réussie', 'success');
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 1000);
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
            notification.style.backgroundColor = type === 'success' ? 'var(--success-color)' : 'var(--error-color)';
            notification.style.display = 'block';
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.style.display = 'none';
                    notification.style.opacity = '1';
                }, 300);
            }, 3000);
        }
        
        // Check auth on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkAuth();
            
            // Add active class to current nav item
            const currentLocation = window.location.pathname;
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            navLinks.forEach(link => {
                const linkPath = link.getAttribute('href');
                if (currentLocation === linkPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>