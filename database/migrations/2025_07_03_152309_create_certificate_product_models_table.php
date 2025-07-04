<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('certificate_product_model', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_model_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['certificate_id', 'product_model_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('certificate_product_model');
    }
};

