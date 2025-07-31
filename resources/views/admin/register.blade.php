<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout utilisateur</title>
</head>
<body>
    <h2>Ajout manuel d’un utilisateur</h2>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.user.store') }}">
        @csrf
        <input name="name" placeholder="Nom"><br>
        <input name="email" placeholder="Email" type="email"><br>
        <select name="role">
            <option>Administrateur</option>
            <option>Préposé aux clients résidentiels</option>
            <option>Préposé aux clients d’affaire</option>
        </select><br>
        <input name="password" placeholder="Mot de passe" type="password"><br>
        <input name="password_confirmation" placeholder="Confirmez" type="password"><br>
        <button type="submit">Créer</button>
    </form>
</body>
</html>
