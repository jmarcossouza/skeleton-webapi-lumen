<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $auth;
    public function __construct()
    {
        //$this->auth = app('auth');
        //$this->middleware('auth:api', ['except' => ['login', 'store', 'verificarEmail', 'recuperar_senha', 'redefinir_senha']]);
    }

    public function store(Request $request, Usuario $usuario)
    {
        $this->validate($request, $usuario->regras_validacao);

        $novo_usuario = $usuario->create($request->all());
        return response()->json($novo_usuario, 201);
    }

    //
}
