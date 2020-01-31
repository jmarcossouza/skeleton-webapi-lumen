<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpsBloqueadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ips_bloqueados', function (Blueprint $table) {
            $table->ipAddress('ip')->primary();
            $table->string('motivo', 200)->nullable(true)->default(null);
            $table->dateTime('expira_em', 0)->nullable(true)->default(null);
            $table->dateTime('created_at', 0)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ips_bloqueados');
    }
}
