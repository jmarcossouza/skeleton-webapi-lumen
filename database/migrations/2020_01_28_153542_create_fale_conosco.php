<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaleConosco extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fale_conosco', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->unsigned();
            $table->tinyInteger('assuntos_fale_conosco_id')->unsigned();
            $table->string('nome', 60);
            $table->string('email', 60)->nullable(true)->default(null);
            $table->string('mensagem', 8000);
            $table->dateTime('visualizado_em')->nullable(true)->default(null);
            $table->dateTime('created_at')->useCurrent();

            $table->foreign('assuntos_fale_conosco_id')->references('id')->on('assuntos_fale_conosco')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fale_conosco');
    }
}
