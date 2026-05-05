<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $fillable = [
        'titulo', 'descricao', 'tempo_preparo', 'dificuldade',
        'custo_medio', 'endereco_video', 'image', 'status', 'data_publicacao', 'user_id',
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

    public function imagens()
{
    return $this->hasMany(ReceitaImagem::class)->orderBy('ordem');
}

public function imagemPrincipal(): ?ReceitaImagem
{
    return $this->imagens->firstWhere('principal', true)
        ?? $this->imagens->first();
}

    public function curtidas()
    {
        return $this->hasMany(Curtida::class);
    }

     // Verifica se o usuário autenticado já curtiu
    public function curtidaPorMim(): bool
    {
        if (!auth()->check()) return false;
        return $this->curtidas()->where('user_id', auth()->id())->exists();
    }

    public function estaCompleta(): bool
    {
        return $this->ingredientes()->exists() && $this->passos()->exists();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

