<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('impostazioni', function (Blueprint $table) {
            $table->decimal('margine_soglia_rossa', 5, 2)->default(40)->after('ricarico_prodotti_default');
            $table->decimal('margine_soglia_verde', 5, 2)->default(50)->after('margine_soglia_rossa');
        });
    }

    public function down(): void
    {
        Schema::table('impostazioni', function (Blueprint $table) {
            $table->dropColumn(['margine_soglia_rossa', 'margine_soglia_verde']);
        });
    }
};