<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->decimal('ekpreis', 10, 2)->nullable()->after('materialgruppe');
        });
    }

    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropColumn('ekpreis');
        });
    }
};
