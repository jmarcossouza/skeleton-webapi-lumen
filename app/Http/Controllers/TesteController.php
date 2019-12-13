<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
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
        throw new CustomException('chavecustomdoerro', "Teste de exception", 400);
    }

    //
}
