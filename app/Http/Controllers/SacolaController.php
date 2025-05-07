<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Sacola;
use App\Models\SacolaItem;
use Illuminate\Http\Request;

class SacolaController extends Controller
{
    public function pegarSacola(Request $request)
    {
        $restauranteId = $request->input('restaurante_id');
        $usuarioId = $request->input('usuario_id');

        $sacola = Sacola::with('itens.produto')
            ->where('restaurante_id', $restauranteId)
            ->where('usuario_id', $usuarioId)
            ->first();

        return response()->json($sacola, 200);
    }

    public function adicionarItem(Request $request)
    {
        $restauranteId = $request->input('restaurante_id');
        $usuarioId = $request->input('usuario_id');
        $produtoId = $request->input('produto_id');

        $produto = Produto::with('categoria')
            ->whereHas('categoria', function ($query) use ($restauranteId) {
                $query->where('restaurante_id', $restauranteId);
            })
            ->find($produtoId);
        
        if(!$produto) {
            return response()->json(['error' => 'Produto não encontrado!', 200]);
        }

        $sacola = Sacola::firstOrCreate(
            ['restaurante_id' => $restauranteId, 'usuario_id' => $usuarioId],
            [
                'restaurante_id' => $restauranteId, 
                'usuario_id' => $usuarioId,
                'valor_total_parcial' => 0
            ]
        );

        $item = SacolaItem::updateOrCreate(
            ['sacola_id' => $sacola->id, 'produto_id' => $produtoId],
        [
            'sacola_id' => $sacola->id,
            'produto_id' => $produtoId,
            'observacoes' => $request->input('observacoes'),
            'quantidade' => $request->input('quantidade'),
            'preco_unitario' => $produto->preco,
        ]);

        $sacola->load(['itens']);

        return response()->json($sacola, 200);
    }
}
