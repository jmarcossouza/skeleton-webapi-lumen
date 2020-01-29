<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAssuntosFaleConosco extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assuntos_fale_conosco', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->tinyIncrements('id');
            $table->string('assunto', 60);
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
        Schema::dropIfExists('assuntos_fale_conosco');
    }

    /**
     * Insere registros na tabela
     *
     * @return void
     */
    private function insert()
    {
        DB::table('assuntos_fale_conosco')->insert([
            ['assunto' => 'Geral'],
            ['assunto' => 'Anunciar'],
            ['assunto' => 'Problemas técnicos/Erros'],
            ['assunto' => 'Dúvidas/Ajuda'],
            ['assunto' => 'Sugestões/Reclamações'],

            //Você pode e deve adicionar outros assuntos aqui...
        ]);
    }

}
