<!DOCTYPE html>
<html>
<head>
    <title>Confirmer mot de passe</title>
    <meta charset="utf-8">
</head>
<body>
    <h2>Confirmez votre mot de passe</h2>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reauth.attempt') }}">
        @csrf
        <label for="password">Mot de passe :</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Confirmer</button>
    </form>
</body>
</html>
