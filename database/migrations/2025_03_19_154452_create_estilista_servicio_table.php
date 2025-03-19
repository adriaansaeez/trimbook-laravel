<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estilista_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('estilista_id')->constrained('estilistas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estilista_servicio');
    }
};
