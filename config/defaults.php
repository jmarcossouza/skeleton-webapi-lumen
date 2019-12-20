<?php

//Para usar:
//usar config('defaults.exp.recuperar_senha');

if (env('APP_ENV') === 'local') {
    //Desenv
    return [
        'exp' => [ //Todas as funções de exp serão em MNUTOS
            'recuperar_senha' => 5,
            'token' => 0
        ],
        'log' => true, //Se deve ser registrado na tabela log as ações realizadas na aplicação.
        'usu_ver_email' => true //Se o usuário deve ou não verificar o email ao criar a conta.
    ];
} else {
    //Produção
    return [
        'exp' => [
            'recuperar_senha' => 60,
            'token' => 0
        ],
        'log' => true,
        'usu_ver_email' => true
    ];
}