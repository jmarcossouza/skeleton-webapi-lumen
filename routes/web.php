<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'teste'], function () use ($router) {
    $router->get('', 'TesteController@get');
});

$router->group(['prefix' => 'usuario'], function () use ($router) {
    $router->post('novo', 'AuthController@create');
    $router->put('alterar', 'AuthController@update');
    $router->put('alterar-senha', 'AuthController@alterarSenha');
    $router->get('eu', 'AuthController@eu');
    $router->post('login', 'AuthController@login');
    $router->post('reenviar-confirmacao-email', 'AuthController@reenviarConfirmarEmail');
    $router->post('confirmar-email', 'AuthController@confirmarEmail');
    $router->post('esqueci-minha-senha', 'AuthController@esqueciMinhaSenha');
    $router->post('redefinir-senha', 'AuthController@redefinirSenha');
});

$router->group(['prefix' => 'fale-conosco'], function() use ($router) {
    $router->get('assuntos', 'FaleConoscoController@getAssuntos');
    $router->post('', 'FaleConoscoController@create');
    $router->get('', 'FaleConoscoController@getAll');
    $router->get('{id}', 'FaleConoscoController@get');
    $router->put('{id}/marcar-visualizado', 'FaleConoscoController@marcarVisualizado');
    $router->put('{id}/desmarcar-visualizado', 'FaleConoscoController@desmarcarVisualizado');
});

$router->group(['prefix' => 'ips-bloqueados'], function() use ($router) {
    $router->get('todos', 'IpsBloqueadosController@getAll');
    $router->get('', 'IpsBloqueadosController@get');
    $router->post('', 'IpsBloqueadosController@create');
    $router->put('', 'IpsBloqueadosController@update');
    $router->delete('', 'IpsBloqueadosController@destroy');
});
