<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ReceitaImagem extends Model
{
    protected $table = 'receita_imagens';

    protected $fillable = ['receita_id', 'path', 'principal', 'ordem'];

    protected $casts = ['principal' => 'boolean'];

    public function receita()
    {
        return $this->belongsTo(Receita::class);
    }

    public function url(): string
    {
        return asset('storage/' . $this->path);
    }

    protected static function booted(): void
    {
        // Apaga o arquivo físico ao deletar o registro
        static::deleting(function (ReceitaImagem $imagem) {
            Storage::disk('public')->delete($imagem->path);
        });
    }
}
