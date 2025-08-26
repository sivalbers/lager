<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 2. Neue Spalte debitor_nr in abladestellen hinzufügen
        Schema::table('abladestellen', function (Blueprint $table) {
            $table->unsignedBigInteger('debitor_nr')->after('id');

            // Fremdschlüssel setzen → verweist auf debitoren.nr
            $table->foreign('debitor_nr')
                  ->references('nr')
                  ->on('debitoren')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rückgängig machen
        Schema::table('abladestellen', function (Blueprint $table) {
            $table->dropForeign(['debitor_nr']);
            $table->dropColumn('debitor_nr');
        });
    }
};
