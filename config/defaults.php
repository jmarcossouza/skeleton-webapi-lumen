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
        'usuario_confirmar_email' => false, //Se o usuário deve ou não verificar o email ao criar a conta.
        'client_url' => 'http://localhost:4200/#', //Url do Front-End
        'logs' => [ //Defaults dos logs
            'registrar_log' => true, //Se deve ser registrado na tabela logs as ações realizadas na aplicação.
            'excluir_antigos' => false, //Se devem ser excluídos os registros de logs antigos.
            'dias_excluir_antigos' => 180 //Nº de dias que mais antigos que eles os logs serão excluídos
        ]
    ];
} else {
    //Produção
    return [
        'exp' => [
            'redefinir_senha' => 60,
            'jwt' => 20160
        ],
        'usuario_confirmar_email' => true,
        'client_url' => 'https://seusite.com',
        'logs' => [
            'registrar_log' => true,
            'excluir_antigos' => true,
            'dias_excluir_antigos' => 360 //1 ano
        ]
    ];
}
