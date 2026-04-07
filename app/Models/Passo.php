<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passo extends Model
{
    protected $fillable = ['receita_id', 'numero', 'descricao', 'dica'];

    public function receita()
    {
        return $this->belongsTo(Receita::class);
    }
}
