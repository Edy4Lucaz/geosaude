<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up(): void {
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->integer('role')->default(2); 
        $table->string('provincia')->nullable();
        $table->string('municipio')->nullable(); // ADICIONE ESTA LINHA
        $table->rememberToken();
        $table->timestamps();
        $table->softDeletes();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};