<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Fremdschlüssel in `lagerdaten` entfernen
        Schema::table('lagerdaten', function (Blueprint $table) {
            $table->dropForeign('lagerdaten_lagernr_foreign');
        });

        // 2. AUTO_INCREMENT entfernen per SQL (MySQL-spezifisch)
        DB::statement('ALTER TABLE lagerorte MODIFY nr BIGINT UNSIGNED');

        // 3. Primärschlüssel entfernen, Typ ändern und neu setzen
        Schema::table('lagerorte', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('nr', 4)->change();
            $table->primary('nr');
        });

        // 4. `lagerdaten.lagernr` auf VARCHAR(4) ändern
        Schema::table('lagerdaten', function (Blueprint $table) {
            $table->string('lagernr', 4)->change();

            // Fremdschlüssel neu setzen
            $table->foreign('lagernr')
                ->references('nr')
                ->on('lagerorte')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lagerorte', function (Blueprint $table) {
            //
        });
    }
};
