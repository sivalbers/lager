<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterialgruppeToArtikelTable extends Migration
{
    public function up(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->string('materialgruppe', 20)->after('einheit'); // passe 'artikel' ggf. an letzte vorhandene Spalte an
        });
    }

    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropColumn('materialgruppe');
        });
    }
}
