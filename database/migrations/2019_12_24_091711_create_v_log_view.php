<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateVLogView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW v_logs AS
            select logs.usuario_id, logs.data, logs.ip, acoes_log.acao
            from logs
            inner join acoes_log on acoes_log.id = logs.acao_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop view if exists v_logs");
    }
}
