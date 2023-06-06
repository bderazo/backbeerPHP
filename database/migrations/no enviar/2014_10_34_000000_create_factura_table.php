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
        Schema::create('factura', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('recibo_id')
                ->constrained('recibo')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignUuid('cliente_id')
                ->constrained('cliente')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->double('valor_total');
            $table->double('impuestos_total');
            $table->integer('items');
            $table->integer('estado');
            $table->double('descuento_total');
            $table->string('num_factura');
            $table->string('clave_acceso');
            $table->string('url_comprobante');
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
        Schema::dropIfExists('factura');
    }
};