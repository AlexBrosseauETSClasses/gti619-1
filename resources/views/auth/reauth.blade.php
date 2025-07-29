@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Confirmation du mot de passe</h2>
    <form method="POST" action="{{ route('reauth.perform') }}">
        @csrf
        <div>
            <label for="password">Mot de passe actuel :</label>
            <input type="password" name="password" required>
            @error('password')
                <div style="color: red">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit">Confirmer</button>
    </form>
</div>
@endsection
