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


        // 1. Spalte abladestelle_id aus debitoren entfernen
        Schema::table('debitoren', function (Blueprint $table) {
            $table->dropForeign('debitoren_abladestelle_id_foreign');
            if (Schema::hasColumn('debitoren', 'abladestelle_id')) {
                $table->dropColumn('abladestelle_id');
            }
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {


        Schema::table('debitoren', function (Blueprint $table) {
            $table->unsignedBigInteger('abladestelle_id')->nullable()->after('netzregion');
        });
    }
};
