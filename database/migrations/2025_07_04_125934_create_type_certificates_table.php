<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_certificates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cliente_id'); // Referencia externa (por API)
            $table->unsignedBigInteger('fabrica_id'); // Referencia externa (por API)
            $table->foreignId('activity_file_id')->nullable()->constrained('activity_files')->onDelete('set null');
            $table->string('certificate')->unique();
            $table->date('certificate_date')->unique();
            $table->string('test_report_number')->nullable();
            $table->date('test_report_date')->nullable();
            $table->tinyInteger('area');
            $table->boolean('active')->default(true);

            $table->timestamps();
        });

        Schema::create('type_certificate_product_model', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_certificate_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_model_id')->constrained()->onDelete('cascade');

            $table->unique(['type_certificate_id', 'product_model_id'], 'type_cert_product_model_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_certificate_product_model');
        Schema::dropIfExists('type_certificates');
    }
};

