<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;

class ReceitaController extends Controller
{
    // Exibir todas as receitas
    public function index()
    {
        $receitas = Receita::all();
        return view('receitas.index', compact('receitas'));
    }

    // Formulário de criação
    public function create()
    {
        return view('receitas.create');
    }

    // Salvar nova receita
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'descricao' => 'required',
        ]);

        Receita::create($request->all());

        return redirect()->route('receitas.index')->with('success', 'Receita criada com sucesso!');
    }

    // Formulário de edição
    public function edit(Receita $receita)
    {
        return view('receitas.edit', compact('receita'));
    }

    // Atualizar receita
    public function update(Request $request, Receita $receita)
    {
        $request->validate([
            'titulo' => 'required',
            'descricao' => 'required',
        ]);

        $receita->update($request->all());

        return redirect()->route('receitas.index')->with('success', 'Receita atualizada com sucesso!');
    }

    // Deletar receita
    public function destroy(Receita $receita)
    {
        $receita->delete();
        return redirect()->route('receitas.index')->with('success', 'Receita deletada com sucesso!');
    }
}
