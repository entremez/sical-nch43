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
        Schema::create('approval_certificates', function (Blueprint $table) {
            $table->id();

            // Relación con certificado tipo
            $table->foreignId('type_certificate_id')->constrained()->onDelete('cascade');

            // Identificador de resolución o control
            $table->string('approval_number')->nullable();
            $table->date('approval_date')->nullable();
            $table->string('sec_number')->nullable();

            // Sistema al que pertenece (1 o 2)
            $table->unsignedTinyInteger('system_type')->default(1); // 1 = Sistema 1, 2 = Sistema 2

            // Documento adicional requerido solo para Sistema 2
            $table->string('factory_audit_document')->nullable(); // Path o nombre del archivo
            $table->date('factory_audit_date')->nullable();

            // Fecha de emisión del certificado de aprobación
            $table->date('approval_date')->nullable();

            // Estado del certificado
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_certificates');
    }
};
