<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detrazione_varianti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detrazione_id')->constrained('detrazioni')->onDelete('cascade');
            $table->string('tipo_immobile');
            $table->decimal('percentuale', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detrazione_varianti');
    }
};