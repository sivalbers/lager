<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('debitoren', function (Blueprint $table) {
            $table->unsignedInteger('nr')->primary(); // <-- hier 'unsignedInteger'
            $table->string('name');
            $table->string('netzregion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('debitoren');
    }
};
