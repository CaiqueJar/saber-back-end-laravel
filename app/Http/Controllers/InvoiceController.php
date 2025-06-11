<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
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

        $usuario = Usuario::where('cpf', $cpf)->firstOrFail();

        $pedidosPorMes = Pedido::select(
                DB::raw("DATE_FORMAT(criado_em, '%Y%m') as mes"),
                DB::raw('COUNT(*) as qtd_pedidos')
            )
            ->where('usuario_id', $usuario->id)
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
}
