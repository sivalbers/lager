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
        Schema::table('protokoll', function (Blueprint $table) {
            // Alte Spalte entfernen (varchar)
            if (Schema::hasColumn('protokoll', 'buchungsgrund')) {
                $table->dropColumn('buchungsgrund');
            }

            // Neue Spalte als Fremdschlüssel anlegen
            $table->foreignId('buchungsgrund_id')
                  ->constrained('buchungsgrund')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    
        Schema::table('protokoll', function (Blueprint $table) {
            $table->dropForeign(['buchungsgrund_id']);
            $table->dropColumn('buchungsgrund_id');
            $table->string('buchungsgrund', 80)->nullable();
        });
    }
};
