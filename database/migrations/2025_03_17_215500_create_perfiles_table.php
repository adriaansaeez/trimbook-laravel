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
        // Tabla de perfiles
        Schema::create('perfiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nombre')->nullable()->default('Sin nombre');
            $table->string('apellidos')->nullable()->default('Sin apellidos');
            $table->string('telefono')->nullable()->default('No especificado');
            $table->string('direccion')->nullable()->default('No especificado');
            $table->string('foto_perfil')->nullable()->default('default.jpg'); // Imagen por defecto
            $table->string('instagram_url')->nullable()->default('No especificado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints(); // Deshabilita restricciones
        Schema::dropIfExists('perfiles');
        Schema::enableForeignKeyConstraints(); // Vuelve a habilitar restricciones
    }
};
