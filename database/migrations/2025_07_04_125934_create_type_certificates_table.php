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
            $table->foreignId('scope_id')->nullable()->constrained()->onDelete('set null');
            $table->text('solicitud')->nullable();
            $table->date('fecha_solicitud')->nullable();
            $table->string('certificate')->nullable();
            $table->date('certificate_date')->nullable();
            $table->text('n_sec')->nullable();
            $table->text('cliente_id'); // Referencia externa (por API)
            $table->text('fabrica_id'); // Referencia externa (por API)            
            $table->string('test_report_number')->nullable();
            $table->date('test_report_date')->nullable();
            $table->text('laboratorio_id'); // Referencia externa (por API) 
            $table->tinyInteger('area');
            $table->text('denominacion_comercial')->nullable();
            $table->text('protocolo_id')->nullable();
            $table->foreignId('activity_file_id')->nullable()->constrained('activity_files')->onDelete('set null');
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

