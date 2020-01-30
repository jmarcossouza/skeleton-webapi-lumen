Lumen (6.2.0)

# Rodar scripts

`composer run [nomescript]`
`php artisan queue:work --queue=[nome_queue]` Abre uma 'thread' pra ficar rodando tudo que chegar naquela queue em específico. `--stop-when-empty` Parar quando estiver vazio. (Acho que, se não tiver outro jeito, o negócio é programar os crons pra executar todo minuto pra CADA queue e usar esse --stop-when-empty).
`php artisan schedule:run` Rodar isso a cada minuto pra executar todas as Schedues, elas estão em skeleton\app\Console\Kernel.php

# Queue names

`confirmar_email_mail` Queue para enviar e-mails de confirmação de novas contas.
`redefinir_senha_mail` Queue para enviar os e-mails de redefinição de senha.
