<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAcoesLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acoes_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->smallIncrements('id');
            $table->string('acao', 100);
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
        Schema::dropIfExists('acoes_log');
    }

    /**
     * Insere registros na tabela
     *
     * @return void
     */
    private function insert()
    {
        DB::table('acoes_log')->insertOrIgnore([
            ['acao' => 'Novo usuário'],
            ['acao' => 'Login'],
            ['acao' => 'Tentativa falha de login'],
            ['acao' => 'Pedido redefinição de senha'],
            ['acao' => 'Redefinição de senha'],
            //Você pode e deve adicionar outras ações aqui...
        ]);
    }
}
