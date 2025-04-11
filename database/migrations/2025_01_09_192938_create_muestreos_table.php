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
        Schema::create('muestreos', function (Blueprint $table) {
            $table->id();

            $table->string('id_proceso');
            $table->string('cliente')->nullable();
            $table->string('contacto')->nullable();
            $table->string('direccion')->nullable();
            $table->string('sistema')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('lote')->nullable();
            $table->integer('muestras')->nullable();
            $table->string('denominacion')->nullable();
            $table->date('fecha')->nullable();
            $table->integer('sicalito')->nullable();
            $table->integer('year')->nullable();
            $table->text('trazabilidad')->nullable();
            $table->text('muestreo')->nullable();
            $table->text('comentarios')->nullable();
            $table->string('fila')->nullable();
            $table->string('columna')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muestreos');
    }
};
