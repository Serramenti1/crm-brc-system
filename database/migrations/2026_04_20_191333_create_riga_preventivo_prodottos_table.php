<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('righe_preventivo_prodotti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preventivo_id')->constrained('preventivi')->onDelete('cascade');
            $table->foreignId('fornitore_id')->nullable()->constrained('fornitori')->nullOnDelete();

            $table->string('descrizione');
            $table->decimal('quantita', 10, 2)->default(1);

            $table->decimal('prezzo_listino', 12, 2)->default(0);

            $table->decimal('sconto_fornitore_1', 5, 2)->default(0);
            $table->decimal('sconto_fornitore_2', 5, 2)->default(0);
            $table->decimal('sconto_fornitore_3', 5, 2)->default(0);

            $table->decimal('costo_netto', 12, 2)->default(0);

            $table->decimal('ricarico_percentuale', 5, 2)->default(0);

            $table->decimal('prezzo_cliente_unitario', 12, 2)->default(0);
            $table->decimal('sconto_cliente_percentuale', 5, 2)->default(0);

            $table->decimal('totale_listino', 12, 2)->default(0);
            $table->decimal('totale_costo', 12, 2)->default(0);
            $table->decimal('totale_cliente', 12, 2)->default(0);

            $table->integer('ordine_visualizzazione')->default(0);
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('righe_preventivo_prodotti');
    }
};