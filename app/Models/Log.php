<?php

namespace App\Models;

use App\Exceptions\InternalException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Log extends Model
{
    protected $fillable = ['usuario_id', 'ip', 'acao_id'];
    public $timestamps = false;

    public static function clientIp(): string
    {
        return Request::ip();
    }

    public static function newLog(int $acao, int $usuario_id): void
    {
        if (config('defaults.log') == true) {
            try {
                self::create([
                    'usuario_id' => $usuario_id,
                    'ip' => self::clientIp(),
                    'acao_id' => $acao
                ]);
            } catch (\Throwable $th) {
                // Não vou dar throw, porque o throw não dará rollback no que já foi feito, então é melhor só não fazer o Log e retornar a mensagem de sucesso normalmente
                // throw new InternalException($th, 'Erro interno no servidor. Por favor, tente novamente.');
            }
        }
    }

    public static function newLogAuth(int $acao): void
    {
        if (app('auth')->user()) {
            self::newLog($acao, app('auth')->user()->getAuthIdentifier());
        } else {
            throw new InternalException(new \Exception("app('auth')->user() retornou nulo.", 500), 'Erro interno no servidor. Por favor, tente novamente.');
        }
    }

    public static function newLogUsuario(int $acao, Usuario $usuario)
    {
        if ($usuario_id = $usuario->getKey() != null) {
            self::newLog($acao, $usuario_id);
        } else {
            throw new InternalException(new \Exception('Método $usuario->getKey() falhou', 500), 'Erro interno no servidor. Por favor, tente novamente.');
        }
    }
}
