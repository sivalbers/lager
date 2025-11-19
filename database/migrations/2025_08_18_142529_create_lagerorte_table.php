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
        Schema::create('lagerorte', function (Blueprint $table) {
            $table->id('nr'); // => ist ein unsignedBigInteger
            $table->string('bezeichnung', 255);
            $table->foreignId('abladestelle_id')->constrained('abladestellen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::dropIfExists('lagerorte');
    }
};
