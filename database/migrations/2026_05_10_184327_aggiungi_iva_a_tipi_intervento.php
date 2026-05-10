<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipi_intervento', function (Blueprint $table) {
            $table->string('modalita_iva')->default('iva_unica')->after('attivo');
            $table->foreignId('impostazione_iva_id')->nullable()->after('modalita_iva')->constrained('impostazioni_iva')->nullOnDelete();
            $table->foreignId('impostazione_iva_secondaria_id')->nullable()->after('impostazione_iva_id')->constrained('impostazioni_iva')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tipi_intervento', function (Blueprint $table) {
            $table->dropForeign(['impostazione_iva_id']);
            $table->dropForeign(['impostazione_iva_secondaria_id']);

            $table->dropColumn([
                'modalita_iva',
                'impostazione_iva_id',
                'impostazione_iva_secondaria_id',
            ]);
        });
    }
};