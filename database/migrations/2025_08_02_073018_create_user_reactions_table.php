<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_reactions', function (Blueprint $table) {
            $table->id();
            $table->string('reaction_key')->unique(); // user_token + '_' + archivo_id
            $table->string('user_token'); // Token de sesión del invitado
            $table->foreignId('archivo_id')->constrained('archivos')->onDelete('cascade');
            $table->enum('type', ['likes', 'unlikes', 'hearts']);
            $table->foreignId('boda_id')->constrained('bodas')->onDelete('cascade'); // Usar boda_id
            $table->string('mesa')->nullable(); // Mesa del invitado
            $table->timestamps();
            
            $table->index(['user_token', 'archivo_id']);
            $table->index(['boda_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_reactions');
    }
};
