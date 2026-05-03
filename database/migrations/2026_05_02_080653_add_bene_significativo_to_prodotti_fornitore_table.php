<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prodotti_fornitore', function (Blueprint $table) {
            $table->boolean('bene_significativo')->default(false)->after('sconto_3');
        });
    }

    public function down(): void
    {
        Schema::table('prodotti_fornitore', function (Blueprint $table) {
            $table->dropColumn('bene_significativo');
        });
    }
};