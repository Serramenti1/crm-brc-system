<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->boolean('richiesto_sal')->default(0)->after('documenti_stampati');
        });
    }

    public function down(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->dropColumn('richiesto_sal');
        });
    }
};