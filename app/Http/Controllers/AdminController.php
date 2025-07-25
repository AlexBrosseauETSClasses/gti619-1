<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // Protéger toutes les routes de ce contrôleur avec le middleware auth et role:Administrateur
        $this->middleware(['auth', 'role:Administrateur']);
    }

    public function index()
    {
        return view('admin.dashboard'); // affiche la vue admin/dashboard.blade.php
    }
}
