<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('designation_id')->constrained()->onDelete('cascade');
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->unique(['name', 'brand_id', 'designation_id']); // evita duplicados
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_models');
    }
};