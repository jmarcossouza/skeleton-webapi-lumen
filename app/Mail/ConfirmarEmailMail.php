<?php

namespace App\Mail;

use App\Models\Usuario;

class ConfirmarEmailMail extends AutomaticMail
{

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Usuario $usuario)
    {
        parent::__construct($usuario);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.confirmar-email')
                    ->subject("Confirme sua conta {$this->app_name}");
    }
}
