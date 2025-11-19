<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('abladestellen', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('debitor_nr');
            $table->foreign('debitor_nr')->references('nr')->on('debitoren');

            $table->string('name', 80);
            $table->string('name2', 80)->nullable();
            $table->string('strasse', 80);
            $table->string('plz', 6);
            $table->string('ort', 100);
            $table->string('kostenstelle', 20)->nullable();
            $table->unsignedTinyInteger('bestellrhythmus')->default(0);
            $table->date('naechstes_belieferungsdatum')->nullable();

            $table->timestamps();


        });
    }

    public function down()
    {
        Schema::dropIfExists('abladestellen');
    }
};
