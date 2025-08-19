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
        Schema::create('lagerdaten', function (Blueprint $table) {
            $table->string('artikelnr', 20);
            $table->unsignedBigInteger('lagernr');
            $table->string('lagerplatz', 10);
            $table->decimal('bestand', 12, 3);


            $table->timestamps();

            // Zusammengesetzter eindeutiger Index
            $table->unique(['artikelnr', 'lagernr', 'lagerplatz']);

            // Foreign Keys
            $table->foreign('artikelnr')->references('artikelnr')->on('artikel')->onDelete('cascade');
            $table->foreign('lagernr')->references('nr')->on('lagerorte')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lagerdaten');
    }
};
