<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->string('foglio_catastale')->nullable()->after('dati_catastali');
            $table->string('mappale_catastale')->nullable()->after('foglio_catastale');
            $table->string('particella_catastale')->nullable()->after('mappale_catastale');
            $table->string('sub_catastale')->nullable()->after('particella_catastale');
        });
    }

    public function down(): void
    {
        Schema::table('commesse', function (Blueprint $table) {
            $table->dropColumn([
                'foglio_catastale',
                'mappale_catastale',
                'particella_catastale',
                'sub_catastale',
            ]);
        });
    }
};