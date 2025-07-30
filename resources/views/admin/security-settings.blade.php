@extends('master')

@section('content')
<div class="container mt-5">
    <h2>Panneau d'administration</h2>
    <p>Bienvenue, administrateur !</p>

    <ul>
        <li><a href="{{ url('/admin/residentiels') }}">Voir les clients résidentiels</a></li>
        <li><a href="{{ url('/admin/affaires') }}">Voir les clients d'affaires</a></li>
    </ul>

    <div class="container mt-5">
        <h2>Paramètres de sécurité</h2>

        {{-- ✅ Message de succès --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ❌ Message d’erreur flash --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- ⚠️ Erreurs de validation --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('security.update') }}">
            @csrf

            <div class="mb-3">
                <label for="min_password_length" class="form-label">Longueur minimale du mot de passe</label>
                <input type="number" id="min_password_length" name="min_password_length" class="form-control"
                       value="{{ old('min_password_length', $settings->min_password_length) }}" min="1" required>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="require_uppercase" name="require_uppercase"
                       {{ old('require_uppercase', $settings->require_uppercase) ? 'checked' : '' }}>
                <label class="form-check-label" for="require_uppercase">Exiger une lettre majuscule</label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="require_numbers" name="require_numbers"
                       {{ old('require_numbers', $settings->require_numbers) ? 'checked' : '' }}>
                <label class="form-check-label" for="require_numbers">Exiger un chiffre</label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="require_special_chars" name="require_special_chars"
                       {{ old('require_special_chars', $settings->require_special_chars) ? 'checked' : '' }}>
                <label class="form-check-label" for="require_special_chars">Exiger un caractère spécial</label>
            </div>

            <div class="mb-3">
                <label for="password_history_count" class="form-label">Nombre d’anciens mots de passe interdits</label>
                <input type="number" id="password_history_count" name="password_history_count" class="form-control"
                       value="{{ old('password_history_count', $settings->password_history_count) }}" min="0" required>
            </div>

            <div class="mb-3">
                <label for="max_login_attempts" class="form-label">Nombre maximal de tentatives de connexion</label>
                <input type="number" id="max_login_attempts" name="max_login_attempts" class="form-control"
                       value="{{ old('max_login_attempts', $settings->max_login_attempts) }}" min="1" required>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</div>
@endsection
