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
        Schema::create('mesas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sucursal_id')
                ->constrained('sucursal')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('num_mesa');
            $table->integer('ubicacion_x');
            $table->integer('ubicacion_y');
            $table->integer('num_personas');
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
        Schema::dropIfExists('mesas');
    }
};