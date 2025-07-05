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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string("nombre")->unique();
            $table->string("descripcion")->nullable();
            $table->timestamps();
        });

        Schema::create('scopes', function (Blueprint $table) {
            $table->id();
            $table->string("nombre")->unique();
            $table->string("descripcion")->nullable();
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->text("cliente");
            $table->text("contacto");
            $table->text("direccion");
            $table->text("fabrica");
            $table->text("direccion_fabrica");
            $table->text("certificado");
            $table->text("qr");
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('set null');
            $table->tinyInteger("active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
        Schema::dropIfExists('scopes');
        Schema::dropIfExists('certificates');
    }
};
