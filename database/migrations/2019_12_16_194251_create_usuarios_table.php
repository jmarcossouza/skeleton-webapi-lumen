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
            $table->char('token_recuperar_senha', 64)->nullable()->default(null)->unique()->comment('Token para o usuário redefinir a senha da conta.');;
            $table->dateTime('exp_recuperar_senha')->nullable()->default(null);
            $table->char('token_verificar_email', 64)->nullable()->unique()->comment('Token para o usuário verificar a conta. Se estiver nulo, é porque a conta está verificada.');
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
