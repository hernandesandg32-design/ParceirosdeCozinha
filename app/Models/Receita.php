<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $fillable = [
        'titulo', 'descricao', 'tempo_preparo', 'dificuldade',
        'custo_medio', 'endereco_video', 'status', 'data_publicacao', 'user_id',
    ];

    protected $casts = [
        'data_publicacao' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ingredientes()
    {
        return $this->hasMany(Ingrediente::class);
    }

    public function passos()
    {
        return $this->hasMany(Passo::class)->orderBy('ordem');
    }

    public function estaCompleta(): bool
    {
        return $this->ingredientes()->exists() && $this->passos()->exists();
    }
}
