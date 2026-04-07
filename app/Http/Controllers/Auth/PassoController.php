<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use App\Models\Passo;

class PassoController extends Controller
{
    public function store(Request $request, Receita $receita)
    {
        $this->authorize('update', $receita);

        $validated = $request->validate([
            'descricao' => 'required|string',
            'dica'      => 'nullable|string|max:500',
        ], [
            'descricao.required' => 'A descrição do passo é obrigatória.',
        ]);

        $numero = $receita->passos()->max('numero') + 1;

        $receita->passos()->create(array_merge($validated, ['numero' => $numero]));

        return back()->with('success', 'Passo adicionado!');
    }

    public function destroy(Receita $receita, Passo $passo)
    {
        $this->authorize('update', $receita);
        $passo->delete();

        // Renumerar os passos restantes
        $receita->passos()->orderBy('numero')->get()->each(function ($p, $i) {
            $p->update(['numero' => $i + 1]);
        });

        return back()->with('success', 'Passo removido.');
    }
}
