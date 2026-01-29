<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up() {
    Schema::table('casos', function (Blueprint $table) {
        $table->string('provincia')->nullable();
        $table->string('municipio')->nullable();
        $table->string('bairro')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casos', function (Blueprint $table) {
            //
        });
    }
};
