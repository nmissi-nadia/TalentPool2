@extends('layouts.app')

@section('title', 'Réinitialisation du mot de passe')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center">Réinitialisation du mot de passe</h2>
            </div>
            <div class="card-body">
                <form id="resetPasswordForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback" id="emailError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <div class="form-text">Le mot de passe doit contenir au moins 8 caractères.</div>
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>
                    
                    <div class="alert alert-danger d-none" id="resetError"></div>
                    <div class="alert alert-success d-none" id="resetSuccess"></div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
                    </div>
                </form>
                
                <div class="mt-3 text-center">
                    <p><a href="/login">Retour à la page de connexion</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset errors and success message
        document.getElementById('emailError').textContent = '';
        document.getElementById('passwordError').textContent = '';
        document.getElementById('resetError').classList.add('d-none');
        document.getElementById('resetSuccess').classList.add('d-none');
        
        // Get form data
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        // Validate form
        let hasError = false;
        
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
        
        if (hasError) {
            return;
        }
        
        // Send password reset request
        fetch(`${API_BASE_URL}/password/reset`, {
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
                // Show success message
                document.getElementById('resetSuccess').textContent = 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.';
                document.getElementById('resetSuccess').classList.remove('d-none');
                
                // Clear form
                document.getElementById('resetPasswordForm').reset();
                
                // Redirect to login page after 3 seconds
                setTimeout(() => {
                    window.location.href = '/login';
                }, 3000);
            } else {
                // Show error message
                if (data.errors) {
                    if (data.errors.email) {
                        document.getElementById('emailError').textContent = data.errors.email[0];
                        document.getElementById('email').classList.add('is-invalid');
                    }
                    
                    if (data.errors.password) {
                        document.getElementById('passwordError').textContent = data.errors.password[0];
                        document.getElementById('password').classList.add('is-invalid');
                    }
                } else {
                    document.getElementById('resetError').textContent = data.message || 'Une erreur est survenue lors de la réinitialisation du mot de passe';
                    document.getElementById('resetError').classList.remove('d-none');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('resetError').textContent = 'Une erreur est survenue. Veuillez réessayer.';
            document.getElementById('resetError').classList.remove('d-none');
        });
    });
</script>
@endsection