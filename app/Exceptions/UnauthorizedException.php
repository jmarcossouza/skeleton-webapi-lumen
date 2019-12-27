<?php

namespace App\Exceptions;

/**
 * Exception que indica que o usuário não pode executar esta determinada ação porque não está logado.
 * `nao_autorizado`
 */
class UnauthorizedException extends CustomException
{
    public function __construct($message = 'Você precisa estar logado para esta ação.')
    {
        parent::__construct('nao_autorizado', $message, 403);
    }
}