<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigInteger('usuario_id')->comment('ID do usuário, referência à tabela de usuarios');
            $table->dateTime('data', 3)->default(DB::raw('CURRENT_TIMESTAMP(3)')); //Se quiser mudar a precisão dos segundos, mudo o segundo parâmetro do dateTime() e também o raw('current_timestamp(..))
            $table->string('ip', 45)->comment('IP do usuário, pode ser tanto IPV6 quanto IPV4');
            $table->smallInteger('acao_id')->unsigned()->comment('ID da ação realizada, olhar na tabela acoes_log');

            $table->primary(['usuario_id', 'data']);
            // $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->foreign('acao_id')->references('id')->on('acoes_log')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
