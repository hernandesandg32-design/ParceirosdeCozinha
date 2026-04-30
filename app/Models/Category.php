<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ['nome', 'emoji', 'slug'];
    public function receitas() {
        return $this->hasMany(Receita::class);
    }
}
