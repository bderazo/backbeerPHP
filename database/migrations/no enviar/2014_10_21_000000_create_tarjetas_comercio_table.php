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
        Schema::create('tarjetas_comercio', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('plan_tarjetas_comercio_id')
                ->constrained('plan_tarjetas_comercio')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignUuid('user_tarjeta_id')->nullable()
                ->constrained('user_tarjeta')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('observaciones');
            $table->string('detalle')->nullable();
            $table->integer('estado')->nullable();
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
        Schema::dropIfExists('tarjetas_comercio');
    }
};