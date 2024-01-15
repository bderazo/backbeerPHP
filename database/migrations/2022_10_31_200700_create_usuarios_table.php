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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('telefono')->nullable();
            $table->string('correo')->unique();
            $table->string('password');
            $table->enum('rol', ['ADMIN','DUEÑO', 'SUPERVISOR', 'VENDEDOR', 'CLIENTE']);
            $table->enum('tipo_usuario', ['GOLD', 'SILVER', 'BRONZE', 'FREE']);
            $table->enum('rol', ['ADMIN','PROPIETARIO','GERENTE','CAJERO','MESERO','COCINERO','USER', 'PROPIETARIO-ONLY']);
            $table->string('identificacion')->unique()->nullable();
            $table->double('puntos')->nullable();
            $table->string('registrado_por');
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
        Schema::dropIfExists('usuarios');
    }
};
