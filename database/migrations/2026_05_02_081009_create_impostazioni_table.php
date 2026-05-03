<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('impostazioni', function (Blueprint $table) {
            $table->id();
            $table->decimal('ricarico_prodotti', 5, 2)->default(50);
            $table->decimal('ricarico_trasporto', 5, 2)->default(50);
            $table->decimal('ricarico_posa', 5, 2)->default(0);
            $table->decimal('ricarico_smaltimento', 5, 2)->default(0);
            $table->timestamps();
        });

        DB::table('impostazioni')->insert([
            'ricarico_prodotti' => 50,
            'ricarico_trasporto' => 50,
            'ricarico_posa' => 0,
            'ricarico_smaltimento' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('impostazioni');
    }
};