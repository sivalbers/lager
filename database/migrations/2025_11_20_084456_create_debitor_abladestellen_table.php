<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_debitor_abladestellen_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('debitor_abladestellen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->foreignId('abladestelle_id')
      ->constrained('abladestellen')   // explizit den korrekten Tabellennamen
      ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('debitor_abladestellen');
    }
};

