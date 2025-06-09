<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nota Fiscal - Pedido {{ $pedido->codigo }}</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        h1, h2 { margin: 0; padding: 0; }
    </style>
</head>
<body>
    <h1>Nota Fiscal Fictícia</h1>
    <p><strong>Pedido:</strong> {{ $pedido->codigo }}</p>
    <p><strong>Cliente:</strong> {{ $pedido->usuario->nome_completo ?? 'N/A' }}</p>
    <p><strong>Restaurante:</strong> {{ $pedido->restaurante->nome_loja ?? 'N/A' }}</p>
    <p><strong>Forma de Pagamento:</strong> {{ ucfirst($pedido->forma_pagamento) }}</p>
    <p><strong>Data:</strong> {{ $pedido->criado_em->format('d/m/Y H:i') }}</p>

    @if($pedido->enderecoEntrega)
        <p><strong>Endereço de Entrega:</strong>
            {{ $pedido->enderecoEntrega->logradouro ?? '' }},
            {{ $pedido->enderecoEntrega->numero ?? '' }},
            {{ $pedido->enderecoEntrega->bairro ?? '' }} -
            {{ $pedido->enderecoEntrega->cidade ?? '' }}/{{ $pedido->enderecoEntrega->estado ?? '' }}
        </p>
    @else
        <p><strong>Entrega:</strong> Retirada no local</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Preço Unitário</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedido->itens as $item)
                <tr>
                    <td>{{ $item->produto->nome }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Valor dos Produtos:</strong> R$ {{ number_format($pedido->valor_produtos, 2, ',', '.') }}</p>
    <p><strong>Taxa de Entrega:</strong> R$ {{ number_format($pedido->taxa_entrega, 2, ',', '.') }}</p>
    <p><strong>Valor Total:</strong> <strong>R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</strong></p>

    @if($pedido->status === 'cancelado')
        <p style="color: red;"><strong>Status:</strong> Cancelado</p>
        <p><strong>Motivo do cancelamento:</strong> {{ $pedido->motivo_cancelamento }}</p>
    @else
        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $pedido->status)) }}</p>
    @endif

    <hr>
    <p style="font-size: 10px; font-style: italic;">Este documento é fictício e gerado apenas para fins acadêmicos.</p>
</body>
</html>
