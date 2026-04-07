<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use App\Models\Receita;
use Illuminate\Http\Request;

class IngredienteController extends Controller
{
    public function store(Request $request, Receita $receita)
    {
        $this->authorize('update', $receita);

        $request->validate([
            'nome'       => ['required', 'string', 'max:150'],
            'quantidade' => ['nullable', 'string', 'max:80'],
        ], [
            'nome.required' => 'O nome do ingrediente é obrigatório.',
            'nome.max'      => 'O nome não pode ultrapassar 150 caracteres.',
            'quantidade.max'=> 'A quantidade não pode ultrapassar 80 caracteres.',
        ]);

        $receita->ingredientes()->create($request->only('nome', 'quantidade'));

        return back()->with('success_ingrediente', 'Ingrediente adicionado!');
    }

    public function destroy(Receita $receita, Ingrediente $ingrediente)
    {
        $this->authorize('update', $receita);
        $ingrediente->delete();
        return back()->with('success_ingrediente', 'Ingrediente removido!');
    }
}
