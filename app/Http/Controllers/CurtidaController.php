<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use Illuminate\Http\Request;

class CurtidaController extends Controller
{
    public function toggle(Receita $receita)
    {
        $curtida = $receita->curtidas()->where('user_id', auth()->id());

        if ($curtida->exists()) {
            $curtida->delete();
            $curtiu = false;
        } else {
            $receita->curtidas()->create(['user_id' => auth()->id()]);
            $curtiu = true;
        }

        // Suporta tanto AJAX quanto redirect normal
        if (request()->expectsJson()) {
            return response()->json([
                'curtiu' => $curtiu,
                'total'  => $receita->curtidas()->count(),
            ]);
        }

        return back();
    }
}
