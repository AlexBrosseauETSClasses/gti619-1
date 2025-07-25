<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all()->toArray();
        return view('client.index', compact('clients'));
    }


    public function residentiels()
    {
        $clients = Client::where('type', 'residentiel')->get();
        return view('client.residentiels', compact('clients'));
    }

    public function affaires()
    {
        $clients = Client::where('type', 'affaire')->get();
        return view('client.affaires', compact('clients'));
    }

    public function create()
    {
        return view('client.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'type' => 'required|in:residentiel,affaire',
        ]);

        Client::create($data);
        return redirect()->back()->with('success', 'Client ajouté.');
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('client.edit', compact('client', 'id'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        Client::whereId($id)->update($data);
        $type = Client::find($id)->type;
        $routeName = $type === 'residentiel' ? 'clients.residentiels' : 'clients.affaires';
        return redirect()->route($routeName)->with('success', 'Client mis à jour.');

    }

    public function destroy($id)
    {
        Client::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Client supprimé.');
    }


}
