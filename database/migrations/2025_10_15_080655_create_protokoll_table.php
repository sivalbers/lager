<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('protokoll', function (Blueprint $table) {
            $table->id();
            $table->dateTime('datum_zeit');
            $table->foreignId('user_id');
            $table->string('artikelnr', 12);
            $table->foreignId('abladestelle_id');
            $table->foreignId('lagerort_id');
            $table->string('lagerplatz', 12);
            $table->decimal('menge', 10, 2);
            $table->string('buchungsgrund', 80);
            $table->string('bemerkung', 80)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('artikelnr')->references('artikelnr')->on('artikel');
            $table->foreign('abladestelle_id')->references('id')->on('abladestellen');
            $table->foreign('lagerort_id')->references('id')->on('lagerorte');
        });
    }

    public function down()
    {
        Schema::dropIfExists('protokoll');
    }
};
