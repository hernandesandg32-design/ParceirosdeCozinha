<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;

class ReceitaController extends Controller
{
    // Exibir todas as receitas
    public function index()
    {
        $receitas = Receita::with(['ingredientes', 'passos'])->latest()->get();
        return view('receitas.index', compact('receitas'));
    }

    // Etapa 1: Formulário de criação (informações básicas)
    public function create()
    {
        return view('receitas.create');
    }

    // Etapa 1: Salvar informações básicas → redireciona para etapa 2
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'          => 'required|string|max:255',
            'descricao'       => 'required|string',
            'tempo_preparo'   => 'nullable|string|max:50',
            'dificuldade'     => 'nullable|in:fácil,médio,difícil',
            'custo_medio'     => 'nullable|numeric|min:0',
            'endereco_video'  => 'nullable|url|max:500',
        ], [
            'titulo.required'    => 'O título da receita é obrigatório.',
            'titulo.max'         => 'O título não pode ultrapassar 255 caracteres.',
            'descricao.required' => 'A descrição da receita é obrigatória.',
            'dificuldade.in'     => 'Selecione uma dificuldade válida.',
            'custo_medio.numeric'=> 'O custo médio deve ser um número.',
            'custo_medio.min'    => 'O custo médio não pode ser negativo.',
            'endereco_video.url' => 'Insira uma URL válida para o vídeo.',
        ]);

        $receita = Receita::create(array_merge($validated, [
            'status'   => 'pendente',
            'user_id'  => auth()->id(),
        ]));

        return redirect()
            ->route('receitas.ingredientes.edit', $receita)
            ->with('success', 'Receita criada! Agora adicione os ingredientes.');
    }

    // Etapa 2: Formulário de ingredientes e passos
    public function editIngredientes(Receita $receita)
    {
        $this->authorize('update', $receita);
        $receita->load(['ingredientes', 'passos']);
        return view('receitas.ingredientes', compact('receita'));
    }

    // Formulário de edição (informações básicas)
    public function edit(Receita $receita)
    {
        $this->authorize('update', $receita);
        return view('receitas.edit', compact('receita'));
    }

    // Atualizar informações básicas
    public function update(Request $request, Receita $receita)
    {
        $this->authorize('update', $receita);

        $validated = $request->validate([
            'titulo'         => 'required|string|max:255',
            'descricao'      => 'required|string',
            'tempo_preparo'  => 'nullable|string|max:50',
            'dificuldade'    => 'nullable|in:fácil,médio,difícil',
            'custo_medio'    => 'nullable|numeric|min:0',
            'endereco_video' => 'nullable|url|max:500',
        ], [
            'titulo.required'    => 'O título da receita é obrigatório.',
            'titulo.max'         => 'O título não pode ultrapassar 255 caracteres.',
            'descricao.required' => 'A descrição da receita é obrigatória.',
            'dificuldade.in'     => 'Selecione uma dificuldade válida.',
            'custo_medio.numeric'=> 'O custo médio deve ser um número.',
            'custo_medio.min'    => 'O custo médio não pode ser negativo.',
            'endereco_video.url' => 'Insira uma URL válida para o vídeo.',
        ]);

        $receita->update($validated);

        return redirect()
            ->route('receitas.edit', $receita)
            ->with('success', 'Receita atualizada com sucesso!');
    }

    // Publicar receita (apenas se completa)
    public function publicar(Receita $receita)
    {
        $this->authorize('update', $receita);

        if (!$receita->estaCompleta()) {
            return back()->with('error', 'A receita precisa ter ao menos um ingrediente e um passo antes de ser publicada.');
        }

        $receita->update([
            'status'          => 'publicada',
            'data_publicacao' => now()->toDateString(),
        ]);

        return redirect()
            ->route('receitas.index')
            ->with('success', 'Receita publicada com sucesso! 🎉');
    }

    // Deletar receita
    public function destroy(Receita $receita)
    {
        $this->authorize('delete', $receita);
        $receita->delete();
        return redirect()->route('receitas.index')->with('success', 'Receita deletada com sucesso!');
    }
}
