<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $auth;
    public function __construct()
    {
        $this->auth = app('auth');
        $this->middleware('auth:api', ['except' => ['login', 'store', 'verificarEmail', 'recuperar_senha', 'redefinir_senha']]);
    }

    public function store(Request $request, Usuario $usuario)
    {
        $this->validate($request, $usuario->regras_validacao);

        $novo_usuario = $usuario->create($request->all());
        return response()->json($novo_usuario, 201);
    }

    public function login(Request $request, Usuario $usuario)
    {
        if ($usuario->where('email', $request->input('email'))->doesntExist()) {
            throw new InvalidRequestException('Não há um usuário com uma conta associada ao e-mail informado.');
        }

        $credenciais = [
            "email" => $request->input('email'),
            "password" => $request->input('senha')
        ];

        if (!$token = $this->auth->attempt($credenciais)) {
            throw new InvalidRequestException("Senha incorreta.");
        }
        return $this->respondWithToken($token);
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token
        ]);
    }

}
