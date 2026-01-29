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
        Schema::create('casos', function (Blueprint $table) {
            $table->id();
            $table->string('paciente_nome');
            $table->string('paciente_bi'); // Será mascarado para Cidadãos (RF-06)
            $table->date('paciente_nascimento');
            $table->string('latitude');  // GIS (RF-03)
            $table->string('longitude'); // GIS (RF-03)
            $table->string('qr_code')->unique(); // Identificador Único (RF-04)
            
            $table->foreignId('doenca_id')->constrained('doencas'); 
            $table->foreignId('user_id')->constrained('users'); // Médico (RF-01)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casos');
    }
};
