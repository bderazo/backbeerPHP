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
        Schema::create('pulsera', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_cliente')
                ->unique()
                ->nullable()
                ->constrained('usuarios')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->double('cupo_maximo')->nullable();
            $table->integer('estado');
            $table->string('tipo_sensor');
            $table->string('codigo_sensor')->unique();
            $table->foreignUuid('usuario_registra')
                ->nullable()
                ->constrained('usuarios')
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
        Schema::dropIfExists('pulsera');
    }
};
