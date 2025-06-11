<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Compras</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Relatório de Compras por Cliente</h2>
    <p><strong>Nome:</strong> {{ $usuario->nome_completo }}</p>
    <p><strong>CPF:</strong> {{ $cpf }}</p>
    <p><strong>Período:</strong> {{ $mesIni }} até {{ $mesFin }}</p>

    <table>
        <thead>
            <tr>
                <th>MÊS</th>
                <th>QTD PEDIDOS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $p)
                <tr>
                    <td>{{ $p->mes }}</td>
                    <td>{{ $p->qtd_pedidos }}</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>TOTAL</strong></td>
                <td><strong>{{ $total }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
