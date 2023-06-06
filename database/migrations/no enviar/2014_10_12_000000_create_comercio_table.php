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
            $table->string('razon_social');
            $table->string('ruc')->unique();
            $table->string('direccion');
            $table->string('telefono');
            $table->string('whatsapp');
            $table->string('correo')->unique();
            $table->string('logo');
            $table->string('sitio_web');
            $table->integer('estado');
            $table->foreignUuid('tipo_comercio')->nullable()
                ->constrained('tipo_comercio')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
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
