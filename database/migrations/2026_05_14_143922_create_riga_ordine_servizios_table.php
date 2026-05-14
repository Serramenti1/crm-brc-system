<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('righe_ordine_servizi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('riga_ordine_id')
                ->constrained('righe_ordini')
                ->onDelete('cascade');

            $table->string('tipo_servizio');
            $table->string('descrizione')->nullable();

            $table->decimal('costo_brc', 12, 2)->default(0);
            $table->decimal('ricarico_percentuale', 5, 2)->default(0);
            $table->decimal('prezzo_cliente', 12, 2)->default(0);

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('righe_ordine_servizi');
    }
};