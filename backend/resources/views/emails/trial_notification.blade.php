<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo ao Período de Testes - {{ config('app.name') }}!</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <h1 style="color: #4CAF50;">Bem-vindo, {{ $user->name }}!</h1>
        <p>Estamos muito felizes por você começar seu período de testes com a nossa plataforma.</p>
        <p>Aqui estão os detalhes:</p>
        <ul>
            <li><strong>Data de Início:</strong> {{ now()->format('d/m/Y') }}</li>
            <li><strong>Data de Término:</strong> {{ $trialEndDate->format('d/m/Y') }}</li>
        </ul>
        <p>Aproveite ao máximo todos os recursos disponíveis durante este período de 14 dias.</p>
        <p>Se precisar de ajuda, entre em contato conosco através de nosso suporte.</p>
        <p style="margin-top: 20px;">Atenciosamente,</p>
        <p><strong>{{ config('app.name') }}</strong></p>
    </div>
</body>
</html>
