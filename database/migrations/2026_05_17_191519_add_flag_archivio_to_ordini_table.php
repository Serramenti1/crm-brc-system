<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->boolean('archivio_saldo_ricevuto')->default(false);
            $table->boolean('archivio_pratica_enea_inviata')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->dropColumn([
                'archivio_saldo_ricevuto',
                'archivio_pratica_enea_inviata',
            ]);
        });
    }
};