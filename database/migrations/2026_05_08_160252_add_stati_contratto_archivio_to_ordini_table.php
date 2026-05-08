<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->boolean('contratto_firmato')->default(false)->after('stato');
            $table->boolean('saldo_finale_ricevuto')->default(false)->after('fattura_saldo_posa_fatta');
            $table->boolean('invio_enea_effettuato')->default(false)->after('saldo_finale_ricevuto');
        });
    }

    public function down(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->dropColumn([
                'contratto_firmato',
                'saldo_finale_ricevuto',
                'invio_enea_effettuato',
            ]);
        });
    }
};