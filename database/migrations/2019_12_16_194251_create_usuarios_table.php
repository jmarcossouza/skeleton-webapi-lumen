<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('ativo')->default(true);
            $table->string('email', 60)->unique();
            $table->char('senha', 60);
            $table->string('nome', 20);
            $table->string('sobrenome', 40);

            $table->char('token_recuperar_senha', 60)->nullable()->default(null)->unique();
            $table->dateTime('exp_recuperar_senha')->nullable()->default(null);
            $table->char('token_verificar_email', 60)->nullable()->unique(); //->default(null)
            //$table->dateTime('exp_verificar_email')->nullable()->default(null); //Não acho que é normal limitar um tempo para ativar a conta
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
}
