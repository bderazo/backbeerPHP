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
        DB::table('tipo_comercio')->insert([
            [
                'id'=>Str::uuid(),
                'nombre_tipo'=>'MAYOR',
                'extra_data'=>'false',
                'codigo'=>'MA01'
            ],
            [
                'id'=>Str::uuid(),
                'nombre_tipo'=>'MENOR',
                'extra_data'=>'false',
                'codigo'=>'ME02' 
            ],
            [
                'id'=>Str::uuid(),
                'nombre_tipo'=>'ELECTRONICO',
                'extra_data'=>'false',
                'codigo'=>'EL03' 
            ],
            [
                'id'=>Str::uuid(),
                'nombre_tipo'=>'INTERNACIONAL',
                'extra_data'=>'false',
                'codigo'=>'IN04' 
            ],
            [
                'id'=>Str::uuid(),
                'nombre_tipo'=>'INFORMAL',
                'extra_data'=>'false',
                'codigo'=>'IN05' 
            ],
            [
                'id'=>Str::uuid(),
                'nombre_tipo'=>'JUSTO',
                'extra_data'=>'false',
                'codigo'=>'JU06'
            ],
            [
                'id'=>Str::uuid(),
                'nombre_tipo'=>'INTERNO',
                'extra_data'=>'false',
                'codigo'=>'IN07' 
            ],
            [
                'id'=>Str::uuid(),
                'nombre_tipo'=>'ESPECIALIZADO',
                'extra_data'=>'false',
                'codigo'=>'ES08' 
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipo_comercio', function (Blueprint $table) {
            //
        });
    }
};
