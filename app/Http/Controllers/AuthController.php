<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalException;
use App\Exceptions\InvalidRequestException;
use App\Jobs\ConfirmarEmailJob;
use App\Jobs\RedefinirSenhaJob;
use App\Models\Log;
use App\Models\Usuario;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    private $auth;
    public function __construct()
    {
        $this->auth = app('auth');
        $this->middleware('auth:api', ['only' => ['update', 'eu', 'alterarSenha']]);
    }

    public function create(Request $request, Usuario $usuario)
    {
        $this->validate($request, $usuario->regras_validacao);

        $novo_usuario = $usuario->create($request->all());

        if ($novo_usuario->token_confirmar_email == null) {
            return response()->json($novo_usuario, 201);
        }
        return response(['mensagem' => 'Sua conta foi criada! Enviamos um e-mail com instruções para a confirmação.'], 200);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'nome'          => 'nullable|min:4|max:20',
            'sobrenome'     => 'nullable|min:4|max:40'
        ]);

        $usuario = $this->auth->user();

        foreach ($request->only(['nome', 'sobrenome']) as $key => $value) { //Cuidado ao alterar isso aqui, o método only() deve conter somente as keys que podem ser alteradas nesse método.
            if ($value != null && $value != '') {
                $usuario->$key = $value;
            }
        }
        $usuario->save();

        return $usuario->toJson();
    }

    public function alterarSenha(Request $request)
    {
        $this->validate($request, [
            'senha_antiga' => 'required|min:6|max:64',
            'nova_senha' => 'required|min:6|max:64'
        ]);

        $usuario = $this->auth->user();
        if ($usuario->verificarSenha($request->senha_antiga) == false) {
            throw new InvalidRequestException('A senha antiga está incorreta.');
        }

        $usuario->senha = Usuario::hashSenha($request->nova_senha);
        $usuario->save();

        return response(['mensagem' => 'A senha foi alterada.']);
    }

    public function eu()
    {
        $usuario = $this->auth->user();
        if ($usuario->ativo == false) {
            throw new InvalidRequestException("Este usuário está inativo, entre em contato conosco.");
        }

        return $usuario->toJson();
    }

    public function login(Request $request, Usuario $usuario)
    {
        $this->validate($request, ['email' => 'required', 'senha' => 'required']);
        $usuario = $usuario->where('email', $request->input('email'))->first();
        if ($usuario == null) {
            throw new InvalidRequestException('Não há um usuário com uma conta associada ao e-mail informado.');
        }

        $credenciais = [
            "email" => $request->input('email'),
            "password" => $request->input('senha')
        ];

        if (!$token = $this->auth->attempt($credenciais)) {
            Log::newLogUsuario(3, $usuario);
            throw new InvalidRequestException("A senha está incorreta.");
        }

        if ($usuario->token_confirmar_email != null) {
            throw new InvalidRequestException("Sua conta ainda não foi confirmada, verifique seu e-mail. Caso necessário, enviaremos outro e-mail de confirmação.");
        }

        if ($usuario->ativo == false) {
            throw new InvalidRequestException("Este usuário está inativo, entre em contato conosco.");
        }

        Log::newLogUsuario(2, $usuario);
        return $this->respondWithToken($token);
    }

    public function reenviarConfirmarEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required']);
        $usuario = Usuario::where('email', $request->input('email'))->first();

        if ($usuario == null) {
            throw new ModelNotFoundException();
        } else if ($usuario->token_confirmar_email == null) {
            throw new InvalidRequestException('Sua conta já está ativada.');
        }

        DB::beginTransaction();
        try {
            $usuario->token_confirmar_email = Usuario::tokenUnico();
            $usuario->save();

            dispatch(new ConfirmarEmailJob($usuario));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return response()->json(['mensagem' => 'Enviamos novamente um e-mail para confirmar sua conta.'], 200);
    }

    public function confirmarEmail(Request $request)
    {
        $this->validate($request, ['token' => 'required']);
        $usuario = Usuario::where('token_confirmar_email', $request->input('token'))->first();

        if ($usuario == null) {
            throw new ModelNotFoundException();
        }

        $usuario->token_confirmar_email = null;
        $usuario->save();

        return response()->json(['mensagem' => 'Sua conta foi confirmada.']);
    }

    public function esqueciMinhaSenha(Request $request)
    {
        $this->validate($request, ['email' => 'required']);
        $usuario = Usuario::where('email', $request->input('email'))->first();

        if ($usuario == null) {
            throw new ModelNotFoundException();
        }

        $usuario->esqueciMinhaSenha();
        //Mail::to($usuario->email)->send(new RedefinirSenhaMail($usuario));
        dispatch(new RedefinirSenhaJob($usuario));

        Log::newLogUsuario(4, $usuario);
        return response()->json(['mensagem' => 'Enviamos um e-mail com instruções para redefinir sua senha.'], 200);
    }

    public function redefinirSenha(Request $request)
    {
        $this->validate(
            $request,
            [
                'token' => 'required',
                'senha' => 'required|min:6|max:64'
            ]
        );
        $usuario = Usuario::where('token_redefinir_senha', $request->input('token'))->first();

        if ($usuario == null) {
            throw new InvalidRequestException('O token enviado é inválido.');
        }

        if (new DateTime($usuario->exp_redefinir_senha) < new DateTime()) {
            DB::beginTransaction();
            try {
                $usuario->token_redefinir_senha = null;
                $usuario->exp_redefinir_senha = null;
                $usuario->save();

                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
            }

            throw new InvalidRequestException('O token enviado expirou.');
        }

        DB::beginTransaction();
        try {
            $usuario->senha = Usuario::hashSenha($request->input('senha'));
            $usuario->token_redefinir_senha = null;
            $usuario->exp_redefinir_senha = null;
            $usuario->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw new InternalException($th, 'Erro inesperado ao alterar a senha, tente novamente mais tarde.');
        }

        Log::newLogUsuario(5, $usuario);
        return response()->json(['mensagem' => 'Sua senha foi alterada.'], 200);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token
        ]);
    }
}
