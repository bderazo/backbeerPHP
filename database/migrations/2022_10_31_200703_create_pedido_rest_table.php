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
        Schema::create('pedido_rest', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('usuario_id')
                ->constrained('usuarios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignUuid('mesa_id')
                ->constrained('mesas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->dateTime('fecha');
            $table->string('descuento_total');
            $table->integer('items_vendidos');
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
        Schema::dropIfExists('pedido_rest');
    }
};