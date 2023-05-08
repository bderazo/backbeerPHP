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
        Schema::create('pago', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('recibo_id')
                ->constrained('recibo')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('tipo_pago');
            $table->double('valor_pago');
            $table->string('descripcion');
            $table->string('url_comprobante');
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
        Schema::dropIfExists('pago');
    }
};