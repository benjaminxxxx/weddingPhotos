<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerPlanningToBodasTable extends Migration
{
    public function up()
    {
        Schema::table('bodas', function (Blueprint $table) {
            $table->boolean('ver_planning')->default(true)->after('subida_activa');
            $table->boolean('ver_galeria')->default(true)->after('ver_planning');
            $table->boolean('ver_principal')->default(true)->after('ver_galeria');
        });
    }

    public function down()
    {
        Schema::table('bodas', function (Blueprint $table) {
            $table->dropColumn(['ver_planning', 'ver_galeria', 'ver_principal']);
        });
    }
}
