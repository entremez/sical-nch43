<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('process_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->string('code')->nullable()->after('certificado');
            $table->text('notes')->nullable()->after('code');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['process_id']);
            $table->dropColumn(['process_id', 'code', 'notes']);
        });
    }
};
