<?php

namespace App\Exceptions;

use Exception;

/**
 * Classe base de exceções internas e inesperadas.
 */
class InternalException extends \Exception
{
    /**
     * Mensagem de erro que será exibida ao client.
     *
     * @var string
     */
    protected $message;
    /**
     * Http status code do erro.
     *
     * @var int
     */
    protected $status_code;
    /**
     * Identificador do erro, para que quem esteja consumindo esta WebApi possa compreender e tratar os erros com base no seu 'tipo'.
     *
     * @var string
     */
    protected $error_key;

    /**
     * Exception interna que causou o erro.
     *
     * @var Exception
     */
    private $internal_exception;

    public function __construct(Exception $internal_exception, string $message = "Erro interno no servidor", int $status_code = 500, string $error_key = "interno")
    {
        $this->internal_exception = $internal_exception;
        $this->error_key = $error_key;
        $this->message = $message;
        $this->status_code = $status_code;
        parent::__construct($message, $status_code);
    }

    final public function getStatusCode(): int
    {
        return $this->status_code;
    }

    final public function getErrorKey(): string
    {
        return $this->error_key;
    }

    /**
     * Retorna a Exception interna que causou o erro.
     *
     * @return \Exception
     */
    final public function getInternalException()
    {
        return $this->internal_exception;
    }

    /**
     * Constrói a resposta de erro pronta para retornar ao client.
     *
     * @return void
     */
    public function response()
    {
        return response()->json(
            [
                "chave_erro"     => $this->getErrorKey(),
                "mensagem"      => $this->getMessage()
            ],
            $this->getStatusCode()
        );
    }
}
