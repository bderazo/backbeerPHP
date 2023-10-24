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
        Schema::create('plan_tarjetas_comercio', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('comercio_id')
                ->constrained('comercio')
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
        Schema::dropIfExists('plan_tarjetas_comercio');
    }
};