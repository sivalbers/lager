<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->unsignedInteger('debitor_nr')->nullable();
            $table->foreignId('abladestelle_id')->nullable()->constrained('abladestellen');
            $table->foreignId('rechtegruppe_id')->nullable()->constrained('rechtegruppe');

            // korrigierter Foreign Key
            $table->foreign('debitor_nr')->references('nr')->on('debitoren');

        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['debitor_nr']);
            $table->dropForeign(['abladestelle_id']);
            $table->dropForeign(['rechtegruppe_id']);
            $table->dropColumn(['debitor_nr', 'abladestelle_id', 'rechtegruppe_id']);
        });
    }
};

