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
        // Tabla intermedia 
        Schema::create('horarios_estilista', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estilista_id')->constrained('estilistas')->onDelete('cascade');
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios_estilistas');
    }
};
