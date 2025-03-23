<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->enum('dia', ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
        });

        // Tabla intermedia estilista_horario
        Schema::create('estilista_horario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estilista_id')->constrained('estilistas')->onDelete('cascade');
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estilista_horario');
        Schema::dropIfExists('horarios');
    }
};
