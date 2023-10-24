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
        Schema::create('egreso', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('caja_id')
                ->constrained('caja')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('descripcion');
            $table->double('valor');
            $table->string('novedades_vendedor');
            $table->string('observaciones_supervisor');
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
        Schema::dropIfExists('egreso');
    }
};