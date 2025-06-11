<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório - Pedidos por Hora</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        .center { text-align: center; }
        img { margin-top: 20px; max-width: 100%; }
    </style>
</head>
<body>
    <h2 class="center">RELATÓRIO - Quantidade de Pedidos por Hora</h2>
    <p><strong>Período:</strong> {{ $dtIni }} até {{ $dtFin }}</p>

    <img src="{{ $graficoUrl }}" alt="Gráfico de Pedidos por Hora">

    <table>
        <thead>
            <tr>
                <th>HORA</th>
                <th>QTD PEDIDOS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dados as $item)
                <tr>
                    <td>{{ $item['hora'] }}</td>
                    <td>{{ $item['qtd'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
