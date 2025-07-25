@extends('master')

@section('content')
<div class="row">
 <div class="col-md-12">
  <br />
  <h3 align="center">Liste des clients affaires</h3>
  <br />
  @if($message = Session::get('success'))
  <div class="alert alert-success">
   <p>{{$message}}</p>
  </div>
  @endif
  <div align="right">
   <a href="{{ url('client/create-affaire') }}" class="btn btn-primary">Ajouter</a>
   <br /><br />
  </div>
  <table class="table table-bordered table-striped">
   <tr>
    <th>Prénom</th>
    <th>Nom</th>
    <th>Modifier</th>
    <th>Supprimer</th>
   </tr>
   @foreach($clients as $row)
   <tr>
    <td>{{ $row->first_name }}</td>
    <td>{{ $row->last_name }}</td>
    <td><a href="{{ route('client.edit', $row->id) }}" class="btn btn-warning">Modifier</a></td>
    <td>
     <form action="{{ route('client.destroy', $row->id) }}" method="POST" class="delete_form">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger">Supprimer</button>
     </form>
    </td>
   </tr>
   @endforeach
  </table>
 </div>
</div>
@endsection
