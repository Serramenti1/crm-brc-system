<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preventivi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commessa_id')->constrained('commesse')->onDelete('cascade');

            $table->string('numero')->nullable();
            $table->integer('versione')->default(1);
            $table->string('stato')->default('bozza');

            $table->decimal('totale_listino_prodotti', 12, 2)->default(0);
            $table->decimal('totale_netto_prodotti', 12, 2)->default(0);
            $table->decimal('totale_servizi_cliente', 12, 2)->default(0);
            $table->decimal('totale_cliente_finale', 12, 2)->default(0);
            $table->decimal('sconto_medio_cliente', 5, 2)->default(0);
            $table->decimal('totale_costo_brc', 12, 2)->default(0);
            $table->decimal('utile_totale', 12, 2)->default(0);

            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preventivi');
    }
};