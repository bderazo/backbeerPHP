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
        Schema::create('producto', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('comercio_id')
                ->constrained('comercio')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('tipo_producto');
            $table->foreignUuid('categoria_producto_id')
                ->constrained('categoria_producto')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('estado');
            $table->string('codigo_barras');
            $table->integer('tipo_impuesto');
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
        Schema::dropIfExists('producto');
    }
};