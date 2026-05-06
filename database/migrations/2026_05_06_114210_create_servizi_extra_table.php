<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servizi_extra', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('costo_brc', 12, 2)->default(0);
            $table->decimal('ricarico_percentuale', 5, 2)->default(0);
            $table->decimal('prezzo_cliente', 12, 2)->default(0);
            $table->boolean('attivo')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servizi_extra');
    }
};