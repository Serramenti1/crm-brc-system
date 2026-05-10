<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->foreignId('tipo_intervento_id')
                ->nullable()
                ->after('tipologia_abitazione')
                ->constrained('tipi_intervento')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->dropForeign(['tipo_intervento_id']);
            $table->dropColumn('tipo_intervento_id');
        });
    }
};