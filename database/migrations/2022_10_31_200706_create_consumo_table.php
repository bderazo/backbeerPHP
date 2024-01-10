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
        Schema::create('consumo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_pulsera')
                ->constrained('pulsera')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->double('total');
            $table->double('precio');
            $table->foreignUuid('id_maquina')
                ->constrained('maquina')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('estado');
            $table->foreignUuid('id_venta')
                ->nullable()
                ->constrained('venta')
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
        Schema::dropIfExists('consumo');
    }
};
