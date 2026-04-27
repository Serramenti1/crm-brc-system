<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riga_preventivo_servizios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('riga_prodotto_id')
                ->constrained('righe_preventivo_prodotti')
                ->onDelete('cascade');

            $table->string('tipo_servizio');
            $table->string('descrizione')->nullable();

            $table->decimal('costo_brc', 10, 2)->default(0);
            $table->decimal('ricarico_percentuale', 5, 2)->default(0);
            $table->decimal('prezzo_cliente', 10, 2)->default(0);

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riga_preventivo_servizios');
    }
};