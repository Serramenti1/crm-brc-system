<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('impostazioni_iva', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('aliquota', 5, 2)->default(0);
            $table->boolean('attiva')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impostazioni_iva');
    }
};