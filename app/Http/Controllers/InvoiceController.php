<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function gerar($id)
    {
        $pedido = Pedido::with([
            'usuario',
            'restaurante',
            'enderecoEntrega',
            'itens.produto'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.nota-fiscal', compact('pedido'));

        return $pdf->download("nota-fiscal-{$pedido->codigo}.pdf");
    }
    
    public function relatorioFrequenciaCompras(Request $request)
    {
        $cpf = $request->input('cpf');
        $mesIni = $request->input('mes_ini');
        $mesFin = $request->input('mes_fin');
        $restauranteId = $request->input('restaurante_id');

        $usuario = Usuario::where('cpf', $cpf)->firstOrFail();

        $pedidosPorMes = Pedido::select(
                DB::raw("DATE_FORMAT(criado_em, '%Y%m') as mes"),
                DB::raw('COUNT(*) as qtd_pedidos')
            )
            ->where('usuario_id', $usuario->id)
            ->where('restaurante_id', $restauranteId)
            ->whereBetween(DB::raw("DATE_FORMAT(criado_em, '%Y%m')"), [$mesIni, $mesFin])
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $totalPedidos = $pedidosPorMes->sum('qtd_pedidos');

        $pdf = Pdf::loadView('pdf.relatorio_1', [
            'usuario' => $usuario,
            'cpf' => $cpf,
            'mesIni' => $mesIni,
            'mesFin' => $mesFin,
            'pedidos' => $pedidosPorMes,
            'total' => $totalPedidos
        ]);

        return $pdf->download('relatorio-frequencia-compras.pdf');
    }


    public function rankingProdutosMaisVendidosPDF(Request $request)
    {
        $dtIni = $request->input('dt_ini');
        $dtFin = $request->input('dt_fin');
        $restauranteId = $request->input('restaurante_id');

        $ranking = PedidoItem::select('produto.nome as nome_produto', DB::raw('SUM(pedido_item.quantidade) as qtd_total'))
            ->join('produto', 'pedido_item.produto_id', '=', 'produto.id')
            ->join('pedido', 'pedido_item.pedido_id', '=', 'pedido.id')
            ->where('pedido.restaurante_id', $restauranteId)
            ->whereBetween('pedido.criado_em', [$dtIni, $dtFin])
            ->groupBy('produto.nome')
            ->orderByDesc('qtd_total')
            ->get();

        $pdf = Pdf::loadView('pdf.ranking_produtos', [
            'ranking' => $ranking,
            'dtIni' => $dtIni,
            'dtFin' => $dtFin,
        ]);

        return $pdf->download('ranking-produtos.pdf');
    }

    public function pedidosPorHoraPDF(Request $request)
    {
        $dtIni = $request->input('dt_ini');
        $dtFin = $request->input('dt_fin');
        $restauranteId = $request->input('restaurante_id');

        $dados = DB::table('pedido')
            ->select(DB::raw('HOUR(criado_em) as hora'), DB::raw('COUNT(*) as qtd'))
            ->whereBetween('criado_em', [$dtIni, $dtFin])
            ->where('restaurante_id', $restauranteId)
            ->groupBy(DB::raw('HOUR(criado_em)'))
            ->orderBy('hora')
            ->get()
            ->map(function ($item) {
                return [
                    'hora' => str_pad($item->hora, 2, '0', STR_PAD_LEFT) . ':00',
                    'qtd' => $item->qtd
                ];
            });
        
        $labels = $dados->pluck('hora')->toArray();
        $quantidades = $dados->pluck('qtd')->toArray();

        $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode([
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Qtd Pedidos',
                    'data' => $quantidades,
                    'backgroundColor' => '#3490dc'
                ]]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Quantidade de Pedidos por Hora'
                ],
                'scales' => [
                    'yAxes' => [[
                        'ticks' => [
                            'beginAtZero' => true,
                            'stepSize' => 1,
                        ]
                    ]]
                ]
            ]
        ]));

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        
        $pdf = Pdf::loadView('pdf.pedidos_por_hora', [
            'dtIni' => $dtIni,
            'dtFin' => $dtFin,
            'graficoUrl' => $chartUrl,
            'dados' => $dados,
        ])->setOptions([
            'isRemoteEnabled' => true
        ]);

        return $pdf->download('relatorio-pedidos-por-hora.pdf');
    }
}
