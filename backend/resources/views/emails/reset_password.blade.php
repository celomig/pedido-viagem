<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir sua Senha - {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <h1 style="color: #4CAF50;">Olá, {{ $user->name }}!</h1>
        <p>Recebemos uma solicitação de redefinição de senha para sua conta.</p>
        <p>Por favor, clique no botão abaixo para redefinir sua senha:</p>
        <a href="{{ $activationUrl }}?token={{ $token }}" style="display: inline-block; padding: 10px 20px; color: white; background-color: #4CAF50; text-decoration: none; border-radius: 5px;">
            Redefinir Senha
        </a>
        <p style="margin-top: 20px;">Se você não fez essa solicitação, nenhuma ação é necessária.</p>
        <p>Atenciosamente,</p>
        <p><strong>{{ config('app.name') }}</strong></p>
    </div>
</body>
</html>
