<?php

namespace App\Exceptions;

/**
 * Exception que indica que a causa da Exception é o BODY do request do client.
 * `request_invalido`
 */
class InvalidBodyRequest extends InvalidRequestException
{
    /**
     * Contém qualquer dado que ajude o client a identificar o erro.
     */
    protected $detalhes;

    public function __construct($message, $detalhes)
    {
        parent::__construct($message);
        $this->detalhes = $detalhes;
    }

    final function getDetalhes()
    {
        return $this->detalhes;
    }

    public function response()
    {
        return response()->json(
            [
                "chave_erro"     => $this->getErrorKey(),
                "mensagem"      => $this->getMessage(),
                "detalhes"      => $this->getDetalhes()
            ],
            $this->getStatusCode()
        );
    }
}
