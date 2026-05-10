<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE commesse MODIFY tipologia_abitazione VARCHAR(255) NULL");
        DB::statement("ALTER TABLE commesse MODIFY tipo_lavoro VARCHAR(255) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE commesse MODIFY tipologia_abitazione ENUM('principale', 'secondaria') NULL");
        DB::statement("ALTER TABLE commesse MODIFY tipo_lavoro ENUM('manutenzione', 'ristrutturazione', 'risparmio_energetico') NULL");
    }
};