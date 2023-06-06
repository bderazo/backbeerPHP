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
        Schema::create('venta', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('caja_id')
                ->constrained('caja')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignUuid('cliente_id')
                ->constrained('cliente')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->dateTime('fecha');
            $table->double('descuento_total');
            $table->integer('items_vendidos');
            $table->integer('estado');
            $table->foreignUuid('pedido_rest_id')
                ->constrained('pedido_rest')
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
        Schema::dropIfExists('venta');
    }
};