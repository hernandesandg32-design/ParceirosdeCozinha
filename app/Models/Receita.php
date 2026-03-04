<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $fillable = [
        'titulo', 'descricao', 'data_publicacao', 'endereco_video',
        'tempo_preparo', 'dificuldade', 'custo_medio', 'status'
    ];
}
