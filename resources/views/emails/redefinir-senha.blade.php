<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Redefinir senha da sua conta {{$app_name}}</title>
    <style>
        html * {
            font-size: 1em !important;
            font-family: Arial !important;
        }
    </style>
</head>

<body>

    <p>Olá, {{$usuario->nome}}.</p>
    <p>Você fez um pedido de redifinição de senha da sua conta {{$app_name}}. Clique no link abaixo para redefinir a senha da sua conta {{$app_name}}.</p>

    <p><a href="{{$client_url}}/usuario/redefinir-senha/{{$usuario->token_redefinir_senha}}" target="_blank">Redefinir senha</a></p>

    <p>Este link irá expirar em <strong>{{($data_expiracao)}}</strong>.</p>

    <div style="margin-top: 50px;">
        <span class="apple-link">Este é um e-mail automático, não o responda.</span>
        <br> Caso você não tenha feito o pedido de redefinição de senha, entre em <a href="{{$contato_url}}">contato</a> conosco.
    </div>

</body>

</html>
