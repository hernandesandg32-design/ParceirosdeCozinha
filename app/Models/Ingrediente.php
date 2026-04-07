<?php
// app/Models/Ingrediente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    protected $fillable = ['receita_id', 'nome', 'quantidade'];

    public function receita()
    {
        return $this->belongsTo(Receita::class);
    }
}
