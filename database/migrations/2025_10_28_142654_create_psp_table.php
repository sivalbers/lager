<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePspTable extends Migration
{
    public function up(): void
    {
        Schema::create('psp', function (Blueprint $table) {
            $table->id(); // Autoincrement ID
            $table->string('netzregion', 20);
            $table->string('kostenstelle', 20);
            $table->string('artikel', 255);
            $table->string('materialgruppe', 20);
            $table->string('format', 20);
            $table->text('beschreibung');
            $table->timestamps(); // created_at und updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psp');
    }
}
