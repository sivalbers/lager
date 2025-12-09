<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('importprotokoll', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('debitornr'); // statt string
            $table->string('lieferscheinnr', 10);
            $table->string('artikelnr', 20);

            $table->timestamps();

            // Foreign Keys
            $table->foreign('debitornr')
                ->references('nr')
                ->on('debitoren')
                ->onDelete('cascade');

            $table->foreign('artikelnr')
                ->references('artikelnr')
                ->on('artikel')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('importprotokoll');
    }
};
