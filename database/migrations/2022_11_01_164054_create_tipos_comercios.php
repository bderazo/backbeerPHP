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
                'nombre_tipo'=>'RESTAURANTE',
                'extra_data'=>'false',
                'codigo'=>'RE01'
            ],
            [
                'id'=>Str::uuid(),
                'nombre_tipo'=>'PASTELERIA',
                'extra_data'=>'false',
                'codigo'=>'PA02' 
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
        Schema::table('tipo_comercio', function (Blueprint $table) {
            //
        });
    }
};
