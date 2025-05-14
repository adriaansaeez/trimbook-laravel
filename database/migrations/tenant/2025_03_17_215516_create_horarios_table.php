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
            $table->string('nombre'); // Columna nombre añadida
            $table->json('horario'); // Almacena el JSON con los días e intervalos
            $table->float('registro_horas_semanales')->default(0);
            $table->timestamps();
        });

        
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
