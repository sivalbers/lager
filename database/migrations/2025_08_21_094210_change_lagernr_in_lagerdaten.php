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
        Schema::table('lagerdaten', function (Blueprint $table) {
            $table->string('lagernr', 4)->change();
        });


        Schema::table('lagerdaten', function (Blueprint $table) {
            $table->dropForeign('lagerdaten_lagernr_foreign');
        });



        // Fremdschlüssel wieder hinzufügen
        Schema::table('lagerdaten', function (Blueprint $table) {
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
        Schema::table('lagerdaten', function (Blueprint $table) {
            $table->dropForeign(['lagernr']);
        });

        // Setze Spalte zurück auf bigint
        Schema::table('lagerdaten', function (Blueprint $table) {
            $table->unsignedBigInteger('lagernr')->change();
        });
    }
};
