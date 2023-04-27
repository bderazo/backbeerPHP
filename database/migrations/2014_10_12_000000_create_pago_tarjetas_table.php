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
        Schema::create('pago_tarjetas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('usuarios_id')
                ->constrained('usuarios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('estado')->nullable();
            $table->string('forma_pago')->nullable();
            $table->string('valor')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('adjuntos')->nullable();
            $table->string('tipo_plan')->nullable();
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
        Schema::dropIfExists('pago_tarjetas');
    }
};