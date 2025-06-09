<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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
}
