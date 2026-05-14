<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->boolean('rilievo_effettuato')->default(false)->after('contratto_firmato');
            $table->boolean('acconto_versato')->default(false)->after('rilievo_effettuato');
        });
    }

    public function down(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->dropColumn([
                'rilievo_effettuato',
                'acconto_versato',
            ]);
        });
    }
};