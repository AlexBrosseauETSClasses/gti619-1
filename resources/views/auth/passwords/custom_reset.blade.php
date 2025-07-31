<!-- resources/views/auth/passwords/custom_reset.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Réinitialiser mot de passe</title>
</head>
<body>
    <h1>Changement du mot de passe</h1>
    <form method="POST" action="{{ route('password.custom.reset.submit') }}">
        @csrf
        <label for="password">Nouveau mot de passe</label><br>
        <input type="password" name="password" required><br><br>

        <label for="password_confirmation">Confirmation</label><br>
        <input type="password" name="password_confirmation" required><br><br>

        <button type="submit">Mettre à jour</button>
    </form>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>
