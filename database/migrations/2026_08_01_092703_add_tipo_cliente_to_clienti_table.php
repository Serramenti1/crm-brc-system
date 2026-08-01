<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clienti', function (Blueprint $table) {
            $table->string('tipo_cliente')->default('privato')->after('id');
            $table->string('ragione_sociale')->nullable()->after('cognome');
            $table->string('nome_referente')->nullable()->after('ragione_sociale');
            $table->string('cognome_referente')->nullable()->after('nome_referente');
            $table->string('codice_sdi')->nullable()->after('partita_iva');
            $table->string('pec')->nullable()->after('codice_sdi');
        });
    }

    public function down(): void
    {
        Schema::table('clienti', function (Blueprint $table) {
            $table->dropColumn(['tipo_cliente', 'ragione_sociale', 'nome_referente', 'cognome_referente', 'codice_sdi', 'pec']);
        });
    }
};