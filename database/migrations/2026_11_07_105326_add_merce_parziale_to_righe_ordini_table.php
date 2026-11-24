<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('righe_ordini', function (Blueprint $table) {
            $table->boolean('merce_parziale')->default(0)->after('merce_arrivata');
            $table->string('note_merce')->nullable()->after('merce_parziale');
        });
    }

    public function down(): void
    {
        Schema::table('righe_ordini', function (Blueprint $table) {
            $table->dropColumn(['merce_parziale', 'note_merce']);
        });
    }
};
