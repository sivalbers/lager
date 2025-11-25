<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('artikeleinrichtung', function (Blueprint $table) {
            $table->id();
            $table->string('artikelnr', 12);
            $table->foreignId('abladestelle_id');
            $table->foreignId('lagerort_id');
            $table->integer('mindestbestand');
            $table->integer('bestellmenge');
            $table->boolean('abladestellenspezifisch')->default(false);
            $table->timestamps();

            $table->foreign('artikelnr')->references('artikelnr')->on('artikel');
            $table->foreign('abladestelle_id')->references('id')->on('abladestellen');
            $table->foreign('lagerort_id')->references('id')->on('lagerorte');

        });
    }

    public function down()
    {
        Schema::dropIfExists('artikeleinrichtung');
    }
};
