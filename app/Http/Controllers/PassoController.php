<?php

namespace App\Http\Controllers;

use App\Models\Passo;
use App\Models\Receita;
use Illuminate\Http\Request;

class PassoController extends Controller
{
    public function store(Request $request, Receita $receita)
    {
        $this->authorize('update', $receita);

        $request->validate([
            'descricao' => ['required', 'string'],
        ], [
            'descricao.required' => 'A descrição do passo é obrigatória.',
        ]);

        // Calcula a próxima ordem automaticamente
        $proxima_ordem = $receita->passos()->max('ordem') + 1;

        $receita->passos()->create([
            'descricao' => $request->descricao,
            'ordem'     => $proxima_ordem,
        ]);

        return back()->with('success_passo', 'Passo adicionado!');
    }

    public function destroy(Receita $receita, Passo $passo)
    {
        $this->authorize('update', $receita);
        $ordem_removida = $passo->ordem;
        $passo->delete();

        // Reordena os passos restantes
        $receita->passos()
            ->where('ordem', '>', $ordem_removida)
            ->decrement('ordem');

        return back()->with('success_passo', 'Passo removido!');
    }
}
