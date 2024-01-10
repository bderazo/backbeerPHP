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
        DB::table('categoria_producto')->insert([
            [
                'id' => Str::uuid(),
                'nombre' => 'Bebidas',
                'estado' => '1'
            ], [
                'id' => Str::uuid(),
                'nombre' => 'Alimentos congelados',
                'estado' => '1'
            ], [
                'id' => Str::uuid(),
                'nombre' => 'Suministros de oficina',
                'estado' => '1'
            ], [
                'id' => Str::uuid(),
                'nombre' => 'Suministros de papelería',
                'estado' => '1'
            ], [
                'id' => Str::uuid(),
                'nombre' => 'Suministros de cocina',
                'estado' => '1'
            ], [
                'id' => Str::uuid(),
                'nombre' => 'Ingredientes',
                'estado' => '1'
            ], [
                'id' => Str::uuid(),
                'nombre' => 'Alimentos frescos',
                'estado' => '1'
            ], [
                'id' => Str::uuid(),
                'nombre' => 'Suministros de limpieza',
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
        Schema::table('categoria_producto', function (Blueprint $table) {
            //
        });
    }
};
