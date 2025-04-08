@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center">Inscription</h2>
            </div>
            <div class="card-body">
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback" id="emailError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <div class="form-text">Le mot de passe doit contenir au moins 8 caractères.</div>
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Je suis un</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" selected disabled>Choisir un rôle</option>
                            <option value="candidat">Candidat</option>
                            <option value="recruteur">Recruteur</option>
                        </select>
                        <div class="invalid-feedback" id="roleError"></div>
                    </div>
                    
                    <div class="alert alert-danger d-none" id="registerError"></div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>
                
                <div class="mt-3 text-center">
                    <p>Vous avez déjà un compte ? <a href="/login">Connectez-vous</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Check if role is provided in URL query params
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const role = urlParams.get('role');
        
        if (role && (role === 'candidat' || role === 'recruteur')) {
            document.getElementById('role').value = role;
        }
    });

    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset errors
        document.getElementById('nameError').textContent = '';
        document.getElementById('emailError').textContent = '';
        document.getElementById('passwordError').textContent = '';
        document.getElementById('roleError').textContent = '';
        document.getElementById('registerError').classList.add('d-none');
        
        // Get form data
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const role = document.getElementById('role').value;
        
        // Validate form
        let hasError = false;
        
        if (!name) {
            document.getElementById('nameError').textContent = 'Le nom est requis';
            document.getElementById('name').classList.add('is-invalid');
            hasError = true;
        }
        
        if (!email) {
            document.getElementById('emailError').textContent = 'L\'email est requis';
            document.getElementById('email').classList.add('is-invalid');
            hasError = true;
        }
        
        if (!password) {
            document.getElementById('passwordError').textContent = 'Le mot de passe est requis';
            document.getElementById('password').classList.add('is-invalid');
            hasError = true;
        } else if (password.length < 8) {
            document.getElementById('passwordError').textContent = 'Le mot de passe doit contenir au moins 8 caractères';
            document.getElementById('password').classList.add('is-invalid');
            hasError = true;
        }
        
        if (!role) {
            document.getElementById('roleError').textContent = 'Le rôle est requis';
            document.getElementById('role').classList.add('is-invalid');
            hasError = true;
        }
        
        if (hasError) {
            return;
        }
        
        // Send registration request
        fetch(`${API_BASE_URL}/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name, email, password, role })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                // Show success notification
                showNotification('Inscription réussie ! Vous pouvez maintenant vous connecter.');
                
                // Redirect to login page
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                // Show validation errors
                if (data.errors) {
                    if (data.errors.name) {
                        document.getElementById('nameError').textContent = data.errors.name[0];
                        document.getElementById('name').classList.add('is-invalid');
                    }
                    
                    if (data.errors.email) {
                        document.getElementById('emailError').textContent = data.errors.email[0];
                        document.getElementById('email').classList.add('is-invalid');
                    }
                    
                    if (data.errors.password) {
                        document.getElementById('passwordError').textContent = data.errors.password[0];
                        document.getElementById('password').classList.add('is-invalid');
                    }
                    
                    if (data.errors.role) {
                        document.getElementById('roleError').textContent = data.errors.role[0];
                        document.getElementById('role').classList.add('is-invalid');
                    }
                } else {
                    // Show general error message
                    document.getElementById('registerError').textContent = data.message || 'Une erreur est survenue lors de l\'inscription';
                    document.getElementById('registerError').classList.remove('d-none');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('registerError').textContent = 'Une erreur est survenue. Veuillez réessayer.';
            document.getElementById('registerError').classList.remove('d-none');
        });
    });
</script>
@endsection