@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center">Connexion</h2>
            </div>
            <div class="card-body">
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback" id="emailError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>
                    
                    <div class="alert alert-danger d-none" id="loginError"></div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
                
                <div class="mt-3 text-center">
                    <p>Vous n'avez pas de compte ? <a href="/register">Inscrivez-vous</a></p>
                    <p><a href="/password/reset">Mot de passe oublié ?</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset errors
        document.getElementById('emailError').textContent = '';
        document.getElementById('passwordError').textContent = '';
        document.getElementById('loginError').classList.add('d-none');
        
        // Get form data
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        // Send login request
        fetch(`${API_BASE_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                // Save token to localStorage
                localStorage.setItem('token', data.token);
                
                // Show success notification
                showNotification('Connexion réussie');
                
                // Redirect to dashboard
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1000);
            } else {
                // Show error message
                document.getElementById('loginError').textContent = data.message || 'Identifiants incorrects';
                document.getElementById('loginError').classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loginError').textContent = 'Une erreur est survenue. Veuillez réessayer.';
            document.getElementById('loginError').classList.remove('d-none');
        });
    });
</script>
@endsection