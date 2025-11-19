<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berechtigung', function (Blueprint $table) {
            // Zeitstempel entfernen, falls vorhanden
            if (Schema::hasColumn('berechtigung', 'created_at')) {
                $table->dropColumn('created_at');
            }

            if (Schema::hasColumn('berechtigung', 'updated_at')) {
                $table->dropColumn('updated_at');
            }

            // Neues Feld "kommentar" hinzufügen
            $table->string('kommentar', 80)->nullable()->after('bezeichnung')->comment('interner Hinweis oder Bemerkung');
        });
    }

    public function down(): void
    {
        Schema::table('berechtigung', function (Blueprint $table) {
            $table->dropColumn('kommentar');

            // Optional: falls du rückgängig machen willst
            $table->timestamps();
        });
    }
};
