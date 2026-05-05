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
        Schema::create('receita_imagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receita_id')->constrained()->cascadeOnDelete();
            $table->string('path');           // caminho no storage
            $table->boolean('principal')->default(false); // imagem principal
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receita_imagens');
    }
};
