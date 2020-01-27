<?php

namespace App\Mail;

use App\Models\Usuario;

class RedefinirSenhaMail extends AutomaticMail
{
    public $data_expiracao;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Usuario $usuario)
    {
        parent::__construct($usuario);
        $this->data_expiracao = ($this->usuario->exp_redefinir_senha)->format('d/m/Y H:i');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.redefinir-senha')
                    ->subject("Redefinição de senha");
    }
}
