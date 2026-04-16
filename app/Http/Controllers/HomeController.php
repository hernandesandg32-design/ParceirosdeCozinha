<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $receitas = Receita::with(['user', 'curtidas', 'ingredientes', 'passos'])
        ->where('status', 'publicada')
        ->withCount('curtidas')
        ->latest()
        ->take(6)
        ->get();

    return view('home', compact('receitas'));
    }
}
