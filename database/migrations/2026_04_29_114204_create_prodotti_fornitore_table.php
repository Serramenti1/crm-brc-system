<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodotti_fornitore', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornitore_id')->constrained('fornitori')->onDelete('cascade');
            $table->string('descrizione');
            $table->decimal('prezzo_listino', 10, 2)->default(0);
            $table->decimal('sconto_1', 5, 2)->default(0);
            $table->decimal('sconto_2', 5, 2)->default(0);
            $table->decimal('sconto_3', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodotti_fornitore');
    }
};