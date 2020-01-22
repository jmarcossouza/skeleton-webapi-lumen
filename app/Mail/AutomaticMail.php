<?php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AutomaticMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $app_name;
    public $client_url;
    public $contato_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Usuario $usuario)
    {
        $this->app_name = env('app_name');
        $this->client_url = config('defaults.client_url');
        $this->contato_url = $this->client_url."/contato";
        $this->usuario = $usuario;
    }
}
