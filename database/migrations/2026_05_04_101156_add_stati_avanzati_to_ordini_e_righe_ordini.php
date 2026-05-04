<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('righe_ordini', function (Blueprint $table) {
            $table->boolean('merce_arrivata')->default(false)->after('in_produzione');
        });

        Schema::table('ordini', function (Blueprint $table) {
            $table->boolean('saldo_merce_ricevuto')->default(false)->after('stato');
            $table->boolean('posa_effettuata')->default(false)->after('saldo_merce_ricevuto');
            $table->boolean('fattura_saldo_posa_fatta')->default(false)->after('posa_effettuata');
        });
    }

    public function down(): void
    {
        Schema::table('righe_ordini', function (Blueprint $table) {
            $table->dropColumn('merce_arrivata');
        });

        Schema::table('ordini', function (Blueprint $table) {
            $table->dropColumn([
                'saldo_merce_ricevuto',
                'posa_effettuata',
                'fattura_saldo_posa_fatta',
            ]);
        });
    }
};