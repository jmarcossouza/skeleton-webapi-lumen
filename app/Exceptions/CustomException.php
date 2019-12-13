<?php

namespace App\Exceptions;

/**
 * Classe base de exceções personalizadas.
 */
class CustomException extends \Exception
{
    /**
     * Mensagem de erro que será exibida ao usuário
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
     * Identificador do erro, para que quem esteja consumindo esta WebApi possa compreender e tratar os erros com base no seu 'tipo'
     *
     * @var string
     */
    protected $error_key;

    public function __construct(string $error_key, string $message, int $status_code)
    {
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
     * Retorna mais detalhes da Exception. NÃO USAR ESSE MÉTODO COMO RETORNO PARA O CLIENT.
     *
     * @return string Retornará o arquivo onde ocorreu a Exception, linha, Http status code e mensagem customizada.
     */
    public function __toString()
    {
        return $this->getFile() . "::{$this->getLine()} : [{$this->status_code}]: {$this->message}\n";
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
