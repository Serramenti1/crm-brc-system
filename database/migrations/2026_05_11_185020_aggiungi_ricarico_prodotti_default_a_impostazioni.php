<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('impostazioni', function (Blueprint $table) {
            $table->decimal('ricarico_prodotti_default', 5, 2)->default(50)->after('iva_ordini');
        });

        DB::table('impostazioni')->update([
            'ricarico_prodotti_default' => 50,
        ]);
    }

    public function down(): void
    {
        Schema::table('impostazioni', function (Blueprint $table) {
            $table->dropColumn('ricarico_prodotti_default');
        });
    }
};