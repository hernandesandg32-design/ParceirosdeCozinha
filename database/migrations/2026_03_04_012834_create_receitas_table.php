<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('receitas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->date('data_publicacao')->nullable();
            $table->string('endereco_video')->nullable();
            $table->string('tempo_preparo')->nullable();
            $table->enum('dificuldade', ['fácil', 'médio', 'difícil'])->default('fácil');
            $table->decimal('custo_medio', 8, 2)->nullable();
            $table->enum('status', ['publicada', 'pendente', 'oculta'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receitas');
    }
};
