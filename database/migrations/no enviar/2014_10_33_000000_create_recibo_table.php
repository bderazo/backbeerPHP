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
        Schema::create('recibo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cliente_id')
                ->constrained('cliente')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignUuid('venta_id')
                ->constrained('venta')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->double('valor_total');
            $table->double('impuestos_total');
            $table->integer('items_vendidos');
            $table->integer('estado');
            $table->double('descuento_total');
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
        Schema::dropIfExists('recibo');
    }
};