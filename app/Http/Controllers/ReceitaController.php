<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use App\Helpers\YoutubeHelper;

class ReceitaController extends Controller
{
    public function create()
    {
        return view('receitas.create');
    }

    public function store(Request $request)
    {
        $resp = $request->validate([
            'titulo'         => ['required', 'string', 'max:255'],
            'descricao'      => ['required', 'string'],
            'tempo_preparo'  => ['nullable', 'string', 'max:50'],
            'dificuldade'    => ['nullable', 'in:fácil,médio,difícil'],
            'custo_medio'    => ['nullable', 'numeric', 'min:0'],
            'endereco_video' => [
                'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    if ($value && !YoutubeHelper::isValid($value)) {
                        $fail('Insira uma URL válida do YouTube (youtube.com/watch?v=... ou youtu.be/...).');
                    }
                },
            ],
        ], [
            'titulo.required'     => 'O título da receita é obrigatório.',
            'titulo.max'          => 'O título não pode ultrapassar 255 caracteres.',
            'descricao.required'  => 'A descrição é obrigatória.',
            'dificuldade.in'      => 'Selecione uma dificuldade válida.',
            'custo_medio.numeric' => 'O custo médio deve ser um número.',
            'custo_medio.min'     => 'O custo médio não pode ser negativo.'
        ]);

        $receita = Receita::create(array_merge($resp, [
            'status'  => 'pendente',
            'user_id' => auth()->id(),
        ]));

        return redirect()
            ->route('receitas.ingredientes.edit', $receita)
            ->with('success', 'Receita criada! Agora adicione os ingredientes e passos.');
    }

    public function editIngredientes(Receita $receita)
    {
        $this->authorize('update', $receita);
        $receita->load(['ingredientes', 'passos']);
        return view('receitas.ingredientes', compact('receita'));
    }

    public function edit(Receita $receita)
    {
        $this->authorize('update', $receita);
        $receita->load(['ingredientes', 'passos']);
        return view('receitas.edit', compact('receita'));
    }

    public function update(Request $request, Receita $receita)
    {
        $this->authorize('update', $receita);

        $res = $request->validate([
            'titulo'         => ['required', 'string', 'max:255'],
            'descricao'      => ['required', 'string'],
            'tempo_preparo'  => ['nullable', 'string', 'max:50'],
            'dificuldade'    => ['nullable', 'in:fácil,médio,difícil'],
            'custo_medio'    => ['nullable', 'numeric', 'min:0'],
            'endereco_video' => [
                'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    if ($value && !YoutubeHelper::isValid($value)) {
                        $fail('Insira uma URL válida do YouTube (youtube.com/watch?v=... ou youtu.be/...).');
                    }
                },
            ],
        ], [
            'titulo.required'     => 'O título da receita é obrigatório.',
            'titulo.max'          => 'O título não pode ultrapassar 255 caracteres.',
            'descricao.required'  => 'A descrição é obrigatória.',
            'dificuldade.in'      => 'Selecione uma dificuldade válida.',
            'custo_medio.numeric' => 'O custo médio deve ser um número.',
            'custo_medio.min'     => 'O custo médio não pode ser negativo.'
        ]);

        $receita->update($res);

        return redirect()
            ->route('receitas.edit', $receita)
            ->with('success', 'Receita atualizada com sucesso!');
    }

    public function publicar(Receita $receita)
    {
        $this->authorize('update', $receita);

        if (!$receita->estaCompleta()) {
            return back()->with('error', 'Adicione ao menos um ingrediente e um passo antes de publicar.');
        }

        $receita->update([
            'status'          => 'publicada',
            'data_publicacao' => now()->toDateString(),
        ]);

        return redirect()
            ->route('receitas.index')
            ->with('success', 'Receita publicada com sucesso! 🎉');
    }

    public function destroy(Receita $receita)
    {
        $this->authorize('delete', $receita);
        $receita->delete();
        return redirect()->route('receitas.index')->with('success', 'Receita deletada!');
    }

    public function show(Receita $receita)
    {
        // Só exibe receitas publicadas (visitantes não veem rascunhos)
        abort_if($receita->status !== 'publicada', 404);

        $receita->load(['user', 'ingredientes', 'passos', 'curtidas']);

        return view('receitas.show', compact('receita'));
    }

    public function index(Request $request)
    {
        $categories = \App\Models\Category::all();

        $query = Receita::with(['user', 'ingredientes', 'category'])
            ->withCount('curtidas')
            ->where('status', 'publicada');

        // Filtro por categoria
        if ($request->filled('categoria')) {
            $query->whereHas(
                'category',
                fn($q) =>
                $q->where('slug', $request->categoria)
            );
        }

        // Filtro por dificuldade
        if ($request->filled('dificuldade')) {
            $query->where('dificuldade', $request->dificuldade);
        }

        // Ordenação
        match ($request->get('ordenar', 'recentes')) {
            'curtidas' => $query->orderByDesc('curtidas_count'),
            default    => $query->latest(),
        };

        $receitas = $query->paginate(9)->withQueryString();

        return view('receitas.index', compact('receitas', 'categories'));
    }
}
