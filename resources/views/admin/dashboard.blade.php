@extends('master')

@section('content')
<div class="container mt-5">
    <h2>Panneau d'administration</h2>
    <p>Bienvenue, administrateur !</p>

    <ul>
        <li><a href="{{ route('clients.residentiels') }}">Voir les clients résidentiels</a></li>
        <li><a href="{{ route('clients.affaires') }}">Voir les clients d'affaires</a></li>
        <li><a href="#">Paramètres de sécurité (à implémenter)</a></li>
    </ul>
</div>
@endsection
