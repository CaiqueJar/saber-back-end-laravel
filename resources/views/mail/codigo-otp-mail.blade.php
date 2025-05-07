<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Código de Verificação</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .email-container {
            max-width: 480px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 20px;
        }

        .codigo {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }

        .footer {
            font-size: 12px;
            color: #999;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/7c/Facebook_New_Logo_%282015%29.svg/2560px-Facebook_New_Logo_%282015%29.svg.png" alt="Logo" class="logo">

        <h2>Seu código de verificação</h2>

        <p>Use o código abaixo para continuar:</p>

        <div class="codigo">{{ $codigo }}</div>

        <p>Se você não solicitou este código, ignore este e-mail.</p>

        <div class="footer">
            &copy; {{ date('Y') }} Grupo Saber. Todos os direitos reservados.
        </div>
    </div>
</body>
</html>
