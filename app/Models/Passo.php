<?php
// app/Models/Passo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passo extends Model
{
    protected $fillable = ['receita_id', 'ordem', 'descricao'];

    public function receita()
    {
        return $this->belongsTo(Receita::class);
    }
}
