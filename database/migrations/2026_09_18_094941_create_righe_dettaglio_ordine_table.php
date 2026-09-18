<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('righe_dettaglio_ordine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('riga_ordine_id')->constrained('righe_ordini')->onDelete('cascade');
            $table->integer('numero_progressivo')->default(0);
            $table->string('posizione')->nullable();
            $table->string('descrizione')->nullable();
            $table->decimal('pezzi', 8, 2)->default(1);
            $table->decimal('costo_listino', 10, 2)->default(0);
            $table->decimal('sconto_1', 5, 2)->default(0);
            $table->decimal('sconto_2', 5, 2)->default(0);
            $table->decimal('sconto_3', 5, 2)->default(0);
            $table->decimal('trasporto', 10, 2)->default(0);
            $table->decimal('posa', 10, 2)->default(0);
            $table->decimal('ricarico_percentuale', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('righe_dettaglio_ordine');
    }
};