<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->dropForeign(['preventivo_id']);

            $table->foreign('preventivo_id')
                ->references('id')
                ->on('preventivi')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->dropForeign(['preventivo_id']);

            $table->foreign('preventivo_id')
                ->references('id')
                ->on('preventivi')
                ->cascadeOnDelete();
        });
    }
};