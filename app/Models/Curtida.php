<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curtida extends Model
{
    protected $fillable = ['user_id', 'receita_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receita()
    {
        return $this->belongsTo(Receita::class);
    }
}
