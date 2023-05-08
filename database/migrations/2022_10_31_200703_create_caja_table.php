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
        Schema::create('caja', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('usuario_id')
                ->constrained('usuarios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignUuid('sucursal_id')
                ->constrained('sucursal')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->dateTime('fecha_apertura');
            $table->double('valor_apertura');
            $table->dateTime('fecha_cierre');
            $table->double('valor_cierre');
            $table->string('novedades_vendedor');
            $table->string('observaciones_supervisor');
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
        Schema::dropIfExists('caja');
    }
};