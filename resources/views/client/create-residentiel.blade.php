@extends('master')

@section('content')
<div class="row">
 <div class="col-md-12">
  <br />
  <h3 align="center">Ajouter un client résidentiel</h3>
  <br />
  @if(count($errors) > 0)
  <div class="alert alert-danger">
   <ul>
   @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
   @endforeach
   </ul>
  </div>
  @endif

  @if(session('success'))
  <div class="alert alert-success">
   <p>{{ session('success') }}</p>
  </div>
  @endif

  <form method="post" action="{{ url('client') }}">
   @csrf
   <div class="form-group">
    <input type="text" name="first_name" class="form-control" placeholder="Prénom" />
   </div>
   <div class="form-group">
    <input type="text" name="last_name" class="form-control" placeholder="Nom" />
   </div>
   <input type="hidden" name="type" value="residentiel">
   <div class="form-group">
    <input type="submit" class="btn btn-primary" value="Soumettre" />
    <a href="{{ route('clients.residentiels') }}" class="btn btn-secondary">Retour</a>
   </div>
  </form>
 </div>
</div>
@endsection
