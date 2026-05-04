<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('impostazioni')) {
            Schema::table('impostazioni', function (Blueprint $table) {
                if (!Schema::hasColumn('impostazioni', 'iva_ordini')) {
                    $table->decimal('iva_ordini', 5, 2)->default(22);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('impostazioni')) {
            Schema::table('impostazioni', function (Blueprint $table) {
                if (Schema::hasColumn('impostazioni', 'iva_ordini')) {
                    $table->dropColumn('iva_ordini');
                }
            });
        }
    }
};