<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tarjeta', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->constrained('usuarios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignUuid('comercio_id')->nullable()
                ->constrained('comercio')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('estado');
            $table->string('img_perfil')->nullable();
            $table->string('img_portada')->nullable();
            $table->string('nombre');
            $table->string('profesion')->nullable();
            $table->string('empresa')->nullable();
            $table->string('acreditaciones')->nullable();
            $table->string('telefono');
            $table->string('direccion')->nullable();
            $table->string('correo')->nullable();
            $table->string('sitio_web')->nullable();
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
        Schema::dropIfExists('user_tarjeta');
    }
};