@extends('layouts.app')

@section('title', 'Connexion')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .navbar {
        display: none;
    }

    footer {
        display: none;
    }

    .login-container {
        width: 100%;
        max-width: 400px;
    }

    .login-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        padding: 40px;
    }

    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-header i {
        font-size: 3rem;
        color: #d32f2f;
        margin-bottom: 10px;
    }

    .login-header h1 {
        font-size: 2rem;
        color: #212121;
        font-weight: bold;
        margin: 0;
    }

    .login-header p {
        color: #666;
        font-size: 0.9rem;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #212121;
        margin-bottom: 8px;
    }

    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #d32f2f;
        box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
    }

    .form-control::placeholder {
        color: #999;
    }

    .btn-login {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #d32f2f 0%, #c62828 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(211, 47, 47, 0.4);
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        margin: 15px 0;
    }

    .checkbox-wrapper input[type="checkbox"] {
        margin-right: 8px;
        cursor: pointer;
    }

    .checkbox-wrapper label {
        margin: 0;
        cursor: pointer;
        color: #666;
        font-size: 0.9rem;
    }

    .demo-users {
        background: #f5f5f5;
        border-radius: 10px;
        padding: 15px;
        margin-top: 25px;
        font-size: 0.85rem;
    }

    .demo-users h5 {
        color: #d32f2f;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .demo-user {
        margin-bottom: 8px;
        color: #666;
    }

    .demo-user strong {
        color: #212121;
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-utensils"></i>
            <h1>Restaurant</h1>
            <p>Système de gestion complet</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Adresse E-mail</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" 
                       placeholder="Votre email" required autofocus>
            </div>

            <div class="form-group">
                <label for="mot_de_passe" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" class="form-control @error('mot_de_passe') is-invalid @enderror" 
                       id="mot_de_passe" name="mot_de_passe" 
                       placeholder="Votre mot de passe" required>
            </div>

            <div class="checkbox-wrapper">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <!-- Utilisateurs de démonstration -->
        <div class="demo-users">
            <h5><i class="fas fa-info-circle"></i> Utilisateurs de test</h5>
            
            <div class="demo-user">
                <strong>Admin:</strong><br>
                admin@restaurant.fr / password123
            </div>
            
            <div class="demo-user">
                <strong>Gérant:</strong><br>
                gerant@restaurant.fr / password123
            </div>
            
            <div class="demo-user">
                <strong>Serveur:</strong><br>
                serveur1@restaurant.fr / password123
            </div>
            
            <div class="demo-user">
                <strong>Cuisinier:</strong><br>
                cuisinier1@restaurant.fr / password123
            </div>
            
            <div class="demo-user">
                <strong>Livreur:</strong><br>
                livreur@restaurant.fr / password123
            </div>
        </div>
    </div>
</div>
@endsection
