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
        Schema::create('galerias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boda_id')->constrained()->onDelete('cascade');
            $table->uuid('upload_token')->nullable(); // token único por sesión
            $table->string('mesa')->nullable();        // por si cambia de mesa
            $table->text('mensaje')->nullable();       // mensaje del usuario/invitado
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
        Schema::dropIfExists('galerias');
    }
};
