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
        Schema::create('maquina', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tipo_cerveza');
            $table->string('ubicacion');
            $table->string('precio');
            $table->double('cantidad');
            $table->integer('estado');
            $table->foreignUuid('id_comercio')
                ->nullable()
                ->constrained('comercio')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('codigo_sensor')->nullable();
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
        Schema::dropIfExists('maquina');
    }
};
