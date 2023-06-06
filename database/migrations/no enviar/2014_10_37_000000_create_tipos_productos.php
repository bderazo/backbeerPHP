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
        DB::table('tipo_producto')->insert([
            [
                'id' => Str::uuid(),
                'nombre' => 'Materia prima',
                'estado' => '1'
            ], [
                'id' => Str::uuid(),
                'nombre' => 'Producto base',
                'estado' => '1'
            ], [
                'id' => Str::uuid(),
                'nombre' => 'Producto elaborado',
                'estado' => '1'
            ], [
                'id' => Str::uuid(),
                'nombre' => 'Combo',
                'estado' => '1'
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
        Schema::table('tipo_producto', function (Blueprint $table) {
            //
        });
    }
};
