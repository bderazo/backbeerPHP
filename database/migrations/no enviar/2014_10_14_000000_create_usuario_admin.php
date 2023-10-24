<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('usuarios')->insert([
            [
                'id' => Str::uuid(),
                'nombres' => 'Admin',
                'apellidos' => 'Master',
                'correo' => 'admin@gmail.com',
                'password' => '123456',
                'rol' => 'ADMIN',
                'identificacion' => '1234567890',
                'registrado_por' => 'Migracion',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            //
        });
    }
};
