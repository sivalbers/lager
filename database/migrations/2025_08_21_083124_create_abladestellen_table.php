<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('abladestellen', function (Blueprint $table) {
            $table->id();
            $table->string('name1');
            $table->string('name2')->nullable();
            $table->string('strasse');
            $table->string('plz', 10);
            $table->string('ort');
            $table->string('lagerort', 10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('abladestellen');
    }
};
