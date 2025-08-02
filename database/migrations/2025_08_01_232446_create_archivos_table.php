<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boda_id')->constrained()->onDelete('cascade');
            $table->foreignId('galeria_id')->constrained()->onDelete('cascade'); // NUEVA COLUMNA
            $table->integer('numero');
            $table->string('tipo')->default('foto'); // 'foto' o 'video'
            $table->string('archivo'); // ruta o path
            $table->boolean('aprobado')->default(false);
            $table->uuid('upload_token')->nullable(); // para permitir eliminar sin login
            $table->string('nombre_opcional')->nullable();
            $table->text('mensaje_opcional')->nullable();
            $table->boolean('oficial')->default(false);
            $table->boolean('galeria')->default(false);
            $table->unsignedInteger('likes')->default(0);
            $table->unsignedInteger('unlikes')->default(0);
            $table->unsignedInteger('hearts')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivos');
    }
};
