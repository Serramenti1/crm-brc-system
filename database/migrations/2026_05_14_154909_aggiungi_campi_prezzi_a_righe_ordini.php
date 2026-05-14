<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('righe_ordini', function (Blueprint $table) {
            $table->string('modalita_calcolo')->default('da_listino')->after('quantita');

            $table->decimal('prezzo_listino', 12, 2)->default(0)->after('modalita_calcolo');
            $table->decimal('costo_netto', 12, 2)->default(0)->after('prezzo_listino');

            $table->decimal('sconto_fornitore_1', 5, 2)->default(0)->after('costo_netto');
            $table->decimal('sconto_fornitore_2', 5, 2)->default(0)->after('sconto_fornitore_1');
            $table->decimal('sconto_fornitore_3', 5, 2)->default(0)->after('sconto_fornitore_2');

            $table->decimal('ricarico_percentuale', 5, 2)->default(0)->after('sconto_fornitore_3');
            $table->boolean('bene_significativo')->default(false)->after('ricarico_percentuale');

            $table->decimal('prezzo_cliente_unitario', 12, 2)->default(0)->after('bene_significativo');
            $table->decimal('totale_cliente', 12, 2)->default(0)->after('prezzo_cliente_unitario');
            $table->decimal('totale_costo', 12, 2)->default(0)->after('totale_cliente');

            $table->text('note')->nullable()->after('totale_costo');
        });
    }

    public function down(): void
    {
        Schema::table('righe_ordini', function (Blueprint $table) {
            $table->dropColumn([
                'modalita_calcolo',
                'prezzo_listino',
                'costo_netto',
                'sconto_fornitore_1',
                'sconto_fornitore_2',
                'sconto_fornitore_3',
                'ricarico_percentuale',
                'bene_significativo',
                'prezzo_cliente_unitario',
                'totale_cliente',
                'totale_costo',
                'note',
            ]);
        });
    }
};