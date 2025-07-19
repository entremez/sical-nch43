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
        Schema::create('reception_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->constrained()->onDelete('cascade');
            $table->string('path'); // ruta del archivo en storage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reception_images');
    }
};
