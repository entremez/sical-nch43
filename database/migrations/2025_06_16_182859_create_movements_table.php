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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('movement_type'); // e.g. internal_input, external_output
            $table->string('dispatch_guide')->nullable();
            $table->string('company')->nullable();
            $table->string('warehouse')->nullable();
            $table->foreignId('base_movement_id')->nullable()->constrained('movements');
            $table->json('expanded')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
