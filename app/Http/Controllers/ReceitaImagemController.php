<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\ReceitaImagem;
use Illuminate\Http\Request;

class ReceitaImagemController extends Controller
{
    // Upload de uma imagem
    public function store(Request $request, Receita $receita)
    {
        $this->authorize('update', $receita);

        $request->validate([
            'imagem' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ], [
            'imagem.required' => 'Selecione uma imagem.',
            'imagem.image'    => 'O arquivo deve ser uma imagem.',
            'imagem.mimes'    => 'Formatos aceitos: JPG, PNG, WEBP.',
            'imagem.max'      => 'A imagem não pode ultrapassar 3MB.',
        ]);

        $path  = $request->file('imagem')->store('receitas/imagens', 'public');
        $ordem = $receita->imagens()->max('ordem') + 1;

        // Se for a primeira imagem, já marca como principal
        $principal = $receita->imagens()->count() === 0;

        $imagem = $receita->imagens()->create([
            'path'      => $path,
            'principal' => $principal,
            'ordem'     => $ordem,
        ]);

        return response()->json([
            'id'        => $imagem->id,
            'url'       => $imagem->url(),
            'principal' => $imagem->principal,
        ]);
    }

    // Deletar uma imagem
    public function destroy(Receita $receita, ReceitaImagem $imagem)
    {
        $this->authorize('update', $receita);

        $eraPrincipal = $imagem->principal;
        $imagem->delete();

        // Se era a principal, promove a próxima
        if ($eraPrincipal) {
            $proxima = $receita->imagens()->first();
            $proxima?->update(['principal' => true]);
        }

        return response()->json(['ok' => true]);
    }

    // Definir imagem principal
    public function setPrincipal(Receita $receita, ReceitaImagem $imagem)
    {
        $this->authorize('update', $receita);

        // Remove principal de todas
        $receita->imagens()->update(['principal' => false]);

        // Define a nova principal
        $imagem->update(['principal' => true]);

        return response()->json(['ok' => true]);
    }
}
