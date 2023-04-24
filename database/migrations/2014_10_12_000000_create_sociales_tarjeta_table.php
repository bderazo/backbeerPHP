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
        Schema::create('sociales_tarjeta', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_tarjeta_id')
                ->constrained('user_tarjeta')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('estado');
            $table->string('text_label')->nullable();
            $table->string('url_label')->nullable();
            $table->string('tipo_social')->nullable();
            $table->string('icon_social')->nullable();
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
        Schema::dropIfExists('sociales_tarjeta');
    }
};