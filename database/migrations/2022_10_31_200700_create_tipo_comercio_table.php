<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_comercio', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('nombre_tipo',['MAYOR','MENOR','ELECTRONICO','INTERNACIONAL','INFORMAL','JUSTO','INTERNO','ESPECIALIZADO']);
            $table->string('codigo');
            $table->string('extra_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_comercio');
    }
};
