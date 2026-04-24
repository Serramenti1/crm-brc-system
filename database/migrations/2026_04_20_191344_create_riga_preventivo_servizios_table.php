<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('righe_preventivo_servizi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('riga_prodotto_id')->constrained('righe_preventivo_prodotti')->onDelete('cascade');

            $table->enum('tipo_servizio', ['posa', 'trasporto', 'smaltimento', 'altro'])->default('altro');

            $table->string('descrizione');
            $table->decimal('quantita', 10, 2)->default(1);

            $table->decimal('costo_unitario', 12, 2)->default(0);
            $table->decimal('costo_totale', 12, 2)->default(0);

            $table->decimal('ricarico_percentuale', 5, 2)->default(0);

            $table->decimal('prezzo_cliente_unitario', 12, 2)->default(0);
            $table->decimal('prezzo_cliente_totale', 12, 2)->default(0);

            $table->integer('ordine_visualizzazione')->default(0);
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('righe_preventivo_servizi');
    }
};
