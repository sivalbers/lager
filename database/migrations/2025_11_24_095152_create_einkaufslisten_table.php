<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('einkaufslisten', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->unsignedInteger('debitor_nr')->nullable();
            $table->foreign('debitor_nr')->references('nr')->on('debitoren')->nullOnDelete();

            $table->foreignId('abladestelle_id')
                ->nullable()
                ->constrained('abladestellen')
                ->nullOnDelete();

            $table->foreignId('lagerort_id')
                ->nullable()
                ->constrained('lagerorte')  // ← hier war der Fehler
                ->nullOnDelete();

            $table->string('artikelnr');
            $table->decimal('menge', 10, 2);
            $table->text('kommentar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('einkaufslisten');
    }
};
