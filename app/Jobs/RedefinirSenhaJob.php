<?php

namespace App\Jobs;

use App\Mail\RedefinirSenhaMail;
use App\Models\Usuario;
use Illuminate\Support\Facades\Mail;

class RedefinirSenhaJob extends Job
{
    public $usuario;
    public $tries = 3; //Número máximo de tentativas
    public $queue = 'redefinir_senha_mail'; //Nome da queue que será rodado.
    public $timeout = 120; //tempo máximo de execução até desistir.
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario->withoutRelations(); //Importante o whitoutRelations() pra não levar as relations da tabela junto na string.
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->usuario->email)->send(new RedefinirSenhaMail($this->usuario));
    }
}
