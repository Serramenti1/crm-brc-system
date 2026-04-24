<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commesse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clienti')->onDelete('cascade');

            $table->string('titolo');
            $table->string('indirizzo_lavoro')->nullable();
            $table->string('citta_lavoro')->nullable();
            $table->string('provincia_lavoro')->nullable();
            $table->string('cap_lavoro')->nullable();

            $table->enum('tipologia_abitazione', ['principale', 'secondaria'])->nullable();
            $table->enum('tipo_lavoro', ['manutenzione', 'ristrutturazione', 'risparmio_energetico'])->nullable();

            $table->string('tipo_detrazione')->nullable();
            $table->decimal('percentuale_detrazione', 5, 2)->nullable();

            $table->string('stato')->default('aperta');
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commesse');
    }
};