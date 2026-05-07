<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->string('ultimo_avanzamento_tipo')->nullable()->after('fattura_saldo_posa_fatta');
            $table->unsignedBigInteger('ultimo_avanzamento_riga_id')->nullable()->after('ultimo_avanzamento_tipo');
        });
    }

    public function down(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->dropColumn([
                'ultimo_avanzamento_tipo',
                'ultimo_avanzamento_riga_id',
            ]);
        });
    }
};