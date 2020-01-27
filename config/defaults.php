<?php

//Para usar:
//usar config('defaults.exp.redefinir_senha');

if (env('APP_ENV') === 'local') {
    //Desenv
    return [
        'exp' => [ //Todas as funções de exp serão em MNUTOS
            'redefinir_senha' => 120,
            'jwt' => null //Tempo em minutos ou null para não expirar
        ],
        'log' => true, //Se deve ser registrado na tabela log as ações realizadas na aplicação.
        'usuario_confirmar_email' => true, //Se o usuário deve ou não verificar o email ao criar a conta.
        'client_url' => 'http://localhost:4200/#' //Url do Front-End
    ];
} else {
    //Produção
    return [
        'exp' => [
            'redefinir_senha' => 60,
            'jwt' => 20160
        ],
        'log' => true,
        'usuario_confirmar_email' => true,
        'client_url' => 'https://seusite.com'
    ];
}
