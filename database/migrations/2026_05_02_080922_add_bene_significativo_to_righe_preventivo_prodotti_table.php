<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('righe_preventivo_prodotti', function (Blueprint $table) {
            $table->boolean('bene_significativo')->default(false)->after('ricarico_percentuale');
        });
    }

    public function down(): void
    {
        Schema::table('righe_preventivo_prodotti', function (Blueprint $table) {
            $table->dropColumn('bene_significativo');
        });
    }
};