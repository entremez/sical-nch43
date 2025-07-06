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
        Schema::create('technical_characteristics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('type_certificate_id')->constrained()->onDelete('cascade');
            $table->foreignId('characteristic_template_id')->constrained()->onDelete('cascade');

            $table->text('valor'); // Guardamos siempre como texto. El tipo real lo determina el template

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_characteristics');
    }
};
