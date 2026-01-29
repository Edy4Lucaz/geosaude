<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void {
    Schema::create('config_alertas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('doenca_id')->constrained('doencas')->onDelete('cascade');
        $table->boolean('alerta_ativo')->default(false);
        $table->enum('modo', ['manual', 'automatico'])->default('manual');
        
        // NOVO: Nível selecionado manualmente pelo Admin
        $table->enum('nivel_atual', ['endemia', 'surto', 'epidemia', 'pandemia'])->default('endemia');

        // Gatilhos para o modo automático
        $table->integer('threshold_surto')->default(5);
        $table->integer('threshold_epidemia')->default(50);
        $table->integer('threshold_pandemia')->default(100); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_alertas');
    }
};
