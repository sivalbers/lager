<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtikettenTable extends Migration
{
    public function up()
    {
        Schema::create('etiketten', function (Blueprint $table) {
            $table->id();
            $table->string('artikelnr', 12);
            $table->unsignedBigInteger('abladestelle_id');
            $table->unsignedBigInteger('lagerort_id');
            $table->string('lagerplatz', 12);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('artikelnr')->references('artikelnr')->on('artikel')->onDelete('cascade');
            $table->foreign('abladestelle_id')->references('id')->on('abladestellen')->onDelete('cascade');
            $table->foreign('lagerort_id')->references('id')->on('lagerorte')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('etiketten');
    }
}
