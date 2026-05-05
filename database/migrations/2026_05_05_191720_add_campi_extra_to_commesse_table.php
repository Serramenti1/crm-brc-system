<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->integer('piano_posa')->nullable()->after('cap_lavoro');
            $table->boolean('autoscala')->default(false)->after('piano_posa');

            $table->text('dati_catastali')->nullable()->after('percentuale_detrazione');
            $table->string('numero_catastale')->nullable()->after('dati_catastali');

            $table->string('pratica_edilizia_tipo')->nullable()->after('numero_catastale');
            $table->string('pratica_edilizia_numero')->nullable()->after('pratica_edilizia_tipo');
            $table->string('pratica_edilizia_protocollo')->nullable()->after('pratica_edilizia_numero');

            $table->boolean('pratica_enea')->default(false)->after('pratica_edilizia_protocollo');
        });
    }

    public function down(): void
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->dropColumn([
                'piano_posa',
                'autoscala',
                'dati_catastali',
                'numero_catastale',
                'pratica_edilizia_tipo',
                'pratica_edilizia_numero',
                'pratica_edilizia_protocollo',
                'pratica_enea',
            ]);
        });
    }
};