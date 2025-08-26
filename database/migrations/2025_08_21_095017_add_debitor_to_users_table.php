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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('debitor')->nullable()->after('id');

            $table->foreign('debitor', 'fk_users_debitor_ref_debitoren_nr')
                  ->references('nr')
                  ->on('debitoren')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_users_debitor_ref_debitoren_nr');
            $table->dropColumn('debitor');
        });
    }
};
