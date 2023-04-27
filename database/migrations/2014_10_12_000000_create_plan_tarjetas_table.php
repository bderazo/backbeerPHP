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
        Schema::create('plan_tarjetas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('usuarios_id')
                ->constrained('usuarios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignUuid('user_tarjeta_id')
                ->constrained('user_tarjeta')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('estado')->nullable();
            $table->string('detalle')->nullable();
            $table->string('precio')->nullable();
            $table->string('tipo_tarjeta');
            $table->foreignUuid('pago_tarjetas_id')
                ->nullable()->constrained('pago_tarjetas')
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
        Schema::dropIfExists('plan_tarjetas');
    }
};