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
        Schema::create('producto_recibo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('recibo_id')
                ->constrained('recibo')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignUuid('producto_id')
                ->constrained('producto')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->double('precio_unitario');
            $table->integer('porcentaje_impuesto');
            $table->double('precio_venta');
            $table->integer('cantidad');
            $table->integer('estado');
            $table->string('comentario');
            $table->double('descuento');
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
        Schema::dropIfExists('producto_recibo');
    }
};