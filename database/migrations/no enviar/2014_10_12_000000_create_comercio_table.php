<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comercio', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre_comercial');
            $table->string('ruc')->unique();
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('logo')->nullable();
            $table->string('sitio_web')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comercio');
    }
};
