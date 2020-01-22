<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirmar conta {{$app_name}}</title>
    <style>
        html * {
            font-size: 1em !important;
            font-family: Arial !important;
        }
    </style>
</head>

<body>

    <p>Olá, {{$usuario->nome}}</p>
    <p>Clique no link abaixo para confirmar sua conta {{$app_name}}.</p>

    <p><a href="{{$client_url}}/usuario/confirmar-email/{{$usuario->token_verificar_email}}" target="_blank">Confirmar conta</a></p>

    <div style="margin-top: 50px;">
        <span class="apple-link">Este é um e-mail automático, não o responda.</span>
        <br> Caso ocorra algum problema, entre em <a href="{{$contato_url}}">contato</a> conosco.
    </div>

</body>

</html>
