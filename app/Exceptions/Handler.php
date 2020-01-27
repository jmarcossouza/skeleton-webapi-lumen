<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        NotFoundHttpException::class,
        CustomException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof InternalException) {
            try {
                DB::table('log_erros')->insert(
                    [
                        'erro'       => $exception->getInternalException()->__toString(),
                        'created_at'    => date('Y-m-d H:i:s')
                    ]
                );
            } catch (\Exception $e) { } //Não fazer nada se acontecer erro.
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof CustomException || $exception instanceof InternalException) {
            return $exception->response();
        } else if ($exception instanceof ValidationException) {
            return Handler::HandleLumenValidationException($exception);
        } else if ($exception instanceof ModelNotFoundException) {
            $e = new CustomException('nao_encontrado', 'Não foi encontrado um registro baseado nos parâmetros enviados.', 404);
            return $e->response();
        } else if ($exception instanceof NotFoundHttpException) {
            $e = new CustomException('rota_nao_encontrada', 'URL/Rota não encontrada.', 404);
            return $e->response();
        }


        return parent::render($request, $exception);
    }

    /**
     * Método que trata as Exceptions do validate() do Laravel/Lumen.
     */
    public static function HandleLumenValidationException(ValidationException $e) {
        $mensagem = '';
        foreach ($e->getResponse()->original as $key => $value) {
            foreach ($value as $keyChild => $valueChild) {
                if ($mensagem === '') {
                    $mensagem.= "$valueChild";
                } else {
                    $mensagem.= " $valueChild";
                }

            }
        }

        $invalid_e = new InvalidBodyRequest($mensagem, $e->errors());

        return $invalid_e->response();
        // return response()->json(
        //     [
        //         "chave_erro" => "request_invalido",
        //         "mensagem"   => $mensagem,
        //         "detalhes"   => $e->getResponse()->original
        //     ], 422
        // );
    }
}
