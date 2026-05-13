<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clienti', function (Blueprint $table) {
            $table->unique('email');
            $table->unique('codice_fiscale');
        });
    }

    public function down(): void
    {
        Schema::table('clienti', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropUnique(['codice_fiscale']);
        });
    }
};