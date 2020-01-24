<?php

use App\Models\Usuario;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->boolean('ativo')->default(true);
            $table->boolean('admin')->default(false)->comment('Se o usuário faz parte da equipe de administração do sistema. Isso pode ser usado para mostrar dados e permitir ações somente à administradores.');
            $table->string('email', 60)->unique();
            $table->char('senha', 60);
            $table->string('nome', 20);
            $table->string('sobrenome', 40);
            $table->char('token_recuperar_senha', 64)->nullable()->default(null)->unique()->comment('Token para o usuário redefinir a senha da conta.');;
            $table->dateTime('exp_recuperar_senha')->nullable()->default(null);
            $table->char('token_confirmar_email', 64)->nullable()->unique()->comment('Token para o usuário confirmar o e-mail a conta. Se estiver nulo, é porque a conta está verificada.');
            $table->timestamps();
        });

        $this->insert();
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

    /**
     * Registros a serem inseridos logo após a criação da tabela.
     *
     * @return void
     */
    private function insert()
    {
        DB::table('usuarios')->insertOrIgnore([
            [
                'admin' => true,
                'email' => 'contato@jmarcossouza.com',
                'senha' => Usuario::hashSenha('123'),
                'nome' => 'João Marcos',
                'sobrenome' => 'Souza',
                'token_confirmar_email' => null,
                'created_at' => date('Y-m-d H:i:s')
            ]
            //Você pode outros usuários aqui
        ]);
    }
}
