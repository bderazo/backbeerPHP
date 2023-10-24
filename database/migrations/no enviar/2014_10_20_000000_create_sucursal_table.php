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
        Schema::create('sucursal', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('comercio_id')
                ->constrained('comercio')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('nombre');
            $table->string('ruc');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('whatsapp');
            $table->string('correo');
            $table->string('secuencial_facturas');
            $table->integer('siguiente_factura');
            $table->string('reponsable');
            $table->integer('estado');
            $table->integer('pax_capacidad');
            $table->integer('es_matriz');
            $table->string('registrado_por');
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
        Schema::dropIfExists('sucursal');
    }
};