<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->decimal('imponibile_4', 12, 2)->default(0)->after('imponibile');
            $table->decimal('imponibile_10', 12, 2)->default(0)->after('imponibile_4');
            $table->decimal('imponibile_22', 12, 2)->default(0)->after('imponibile_10');

            $table->decimal('iva_4', 12, 2)->default(0)->after('iva_importo');
            $table->decimal('iva_10', 12, 2)->default(0)->after('iva_4');
            $table->decimal('iva_22', 12, 2)->default(0)->after('iva_10');

            $table->decimal('totale_iva', 12, 2)->default(0)->after('iva_22');
        });
    }

    public function down(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->dropColumn([
                'imponibile_4',
                'imponibile_10',
                'imponibile_22',
                'iva_4',
                'iva_10',
                'iva_22',
                'totale_iva',
            ]);
        });
    }
};