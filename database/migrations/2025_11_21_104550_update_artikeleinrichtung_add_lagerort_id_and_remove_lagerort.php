<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('artikeleinrichtung', function (Blueprint $table) {

            if (Schema::hasColumn('artikeleinrichtung', 'lagerort')) {
                    $table->dropColumn('lagerort');
            }

            $table->bigInteger('lagerort_id')->nullable()->after('abladestelle_id'); // nach Bedarf positionieren
            $table->foreign('lagerort_id')->references('id')->on('lagerorte')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('artikeleinrichtung', function (Blueprint $table) {
            $table->dropForeign(['lagerort_id']);
            $table->dropColumn('lagerort_id');
            $table->string('lagerort')->nullable();
        });
    }
};
