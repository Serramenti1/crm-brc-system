<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordini', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preventivo_id')->constrained('preventivi')->onDelete('cascade');
            $table->foreignId('commessa_id')->nullable()->constrained('commesse')->nullOnDelete();
            $table->string('numero');
            $table->decimal('imponibile', 12, 2)->default(0);
            $table->decimal('iva_percentuale', 5, 2)->default(22);
            $table->decimal('iva_importo', 12, 2)->default(0);
            $table->decimal('totale_con_iva', 12, 2)->default(0);
            $table->string('stato')->default('aperto');
            $table->timestamps();

            $table->unique('preventivo_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordini');
    }
};