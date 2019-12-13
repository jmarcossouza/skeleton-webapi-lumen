<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Exceptions\InternalException;
use Illuminate\Http\Request;

class TesteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function get(Request $request)
    { 
        $e = new CustomException('chavecustomdoerro', "Teste de exception", 400);
        throw new InternalException($e, "mensagem pro usuário");
    }

    //
}
