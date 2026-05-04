<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('righe_ordini', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordine_id')->constrained('ordini')->onDelete('cascade');
            $table->foreignId('riga_preventivo_prodotto_id')->nullable()->constrained('righe_preventivo_prodotti')->nullOnDelete();
            $table->foreignId('fornitore_id')->nullable()->constrained('fornitori')->nullOnDelete();

            $table->string('descrizione');
            $table->decimal('quantita', 10, 2)->default(1);
            $table->decimal('imponibile', 12, 2)->default(0);

            $table->boolean('inviato')->default(false);
            $table->boolean('co_ricevuta')->default(false);
            $table->boolean('in_produzione')->default(false);

            $table->string('pdf_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('righe_ordini');
    }
};