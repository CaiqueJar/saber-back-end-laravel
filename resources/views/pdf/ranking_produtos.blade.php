<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ranking de Produtos</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>RELATÓRIO 2 - Ranking de produtos mais vendidos</h2>
    <p><strong>Período:</strong> {{ $dtIni }} a {{ $dtFin }}</p>

    <table>
        <thead>
            <tr>
                <th>PRODUTO</th>
                <th>QTD</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ranking as $item)
                <tr>
                    <td>{{ $item->nome_produto }}</td>
                    <td>{{ $item->qtd_total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
