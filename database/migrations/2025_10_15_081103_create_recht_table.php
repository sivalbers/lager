<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recht', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rechtegruppen_id');
            $table->foreignId('berechtigung_id');
            $table->timestamps();

            $table->foreign('rechtegruppen_id')->references('id')->on('rechtegruppe');
            $table->foreign('berechtigung_id')->references('id')->on('berechtigung');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recht');
    }
};
