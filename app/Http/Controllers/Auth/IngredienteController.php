<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use App\Models\Ingrediente;

class IngredienteController extends Controller
{
    public function store(Request $request, Receita $receita)
    {
        $this->authorize('update', $receita);

        $validated = $request->validate([
            'nome'       => 'required|string|max:255',
            'quantidade' => 'nullable|string|max:50',
            'unidade'    => 'nullable|string|max:30',
        ], [
            'nome.required' => 'O nome do ingrediente é obrigatório.',
            'nome.max'      => 'O nome do ingrediente não pode ultrapassar 255 caracteres.',
        ]);

        $ordem = $receita->ingredientes()->max('ordem') + 1;

        $receita->ingredientes()->create(array_merge($validated, ['ordem' => $ordem]));

        return back()->with('success', 'Ingrediente adicionado!');
    }

    public function destroy(Receita $receita, Ingrediente $ingrediente)
    {
        $this->authorize('update', $receita);
        $ingrediente->delete();
        return back()->with('success', 'Ingrediente removido.');
    }
}
