<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riga_preventivo_servizios', function (Blueprint $table) {
            $table->string('categoria')->default('servizi')->after('tipo_servizio');
        });
    }

    public function down(): void
    {
        Schema::table('riga_preventivo_servizios', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};