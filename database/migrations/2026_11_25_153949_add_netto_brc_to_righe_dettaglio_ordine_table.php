<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('righe_dettaglio_ordine', function (Blueprint $table) {
            $table->decimal('netto_brc', 10, 2)->default(0)->after('pezzi');
        });
    }

    public function down(): void
    {
        Schema::table('righe_dettaglio_ordine', function (Blueprint $table) {
            $table->dropColumn('netto_brc');
        });
    }
};