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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->text("cliente");
            $table->text("contacto");
            $table->text("direccion");
            $table->text("fabrica");
            $table->text("direccion_fabrica");
            $table->text("producto");
            $table->text("marca");
            $table->text("modelo");
            $table->text("certificado");
            $table->text("qr");
            $table->tinyInteger("area");
            $table->tinyInteger("active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
