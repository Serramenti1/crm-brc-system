<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->string('pdf_foglio_smaltimento')->nullable();
            $table->string('pdf_contratto_posatori')->nullable();
            $table->string('pdf_contratto_vendita')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ordini', function (Blueprint $table) {
            $table->dropColumn([
                'pdf_foglio_smaltimento',
                'pdf_contratto_posatori',
                'pdf_contratto_vendita',
            ]);
        });
    }
};