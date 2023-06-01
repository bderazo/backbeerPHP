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
        Schema::create('producto_sucursal', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('producto_id')
                ->constrained('producto')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignUuid('sucursal_id')
                ->constrained('sucursal')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('stock');
            $table->integer('estado');
            $table->string('registrado_por');
            $table->double('precio');
            $table->double('alerta_min_stock');
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
        Schema::dropIfExists('producto_sucursal');
    }
};