<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'data_publicacao',
        'endereco_video',
        'tempo_preparo',
        'dificuldade',
        'custo_medio',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ingredientes()
    {
        return $this->hasMany(Ingrediente::class)->orderBy('ordem');
    }

    public function passos()
    {
        return $this->hasMany(Passo::class)->orderBy('numero');
    }

    public function estaCompleta(): bool
    {
        return $this->ingredientes()->count() > 0 && $this->passos()->count() > 0;
    }
}
