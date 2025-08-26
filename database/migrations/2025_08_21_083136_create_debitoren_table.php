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
        Schema::create('debitoren', function (Blueprint $table) {
            $table->unsignedBigInteger('nr')->primary();
            $table->string('name');
            $table->unsignedBigInteger('netzregion');
            $table->unsignedBigInteger('abladestelle_id');
            $table->timestamps();

            $table->foreign('abladestelle_id')->references('id')->on('abladestellen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::dropIfExists('debitoren');
    }
};
