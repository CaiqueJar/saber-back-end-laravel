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
        $usuarioId = $request->input('usuario_id');

        $sacola = Sacola::with(['itens.produto', 'restaurante'])
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

    public function removerItem(Request $request)
    {
        $restauranteId = $request->input('restaurante_id');
        $usuarioId = $request->input('usuario_id');
        $itemId = $request->input('item_id');

        $sacola = Sacola::where('restaurante_id', $restauranteId)
            ->where('usuario_id', $usuarioId)
            ->first();

        if(!$sacola) {
            return response()->json(['error' => 'Sacola não encontrado!', 200]);
        }

        $item = SacolaItem::where('sacola_id', $sacola->id)
            ->where('id', $itemId)
            ->first();
        
        if(!$item) {
            return response()->json(['error' => 'Produto não encontrado!', 200]);
        }

        $item->delete();

        $sacola->load(['itens.produto']);

        if($sacola->itens->count() == 0) {
            $sacola->delete();
            return response()->json([], 200);
        }

        return response()->json($sacola, 200);
    }

    public function alterarQuantidadeItem(Request $request)
    {
        $restauranteId = $request->input('restaurante_id');
        $usuarioId = $request->input('usuario_id');
        $itemId = $request->input('item_id');
        $quantia = $request->input('quantia');

        $sacola = Sacola::where('restaurante_id', $restauranteId)
            ->where('usuario_id', $usuarioId)
            ->first();

        if(!$sacola) {
            return response()->json(['error' => 'Sacola não encontrado!', 200]);
        }

        $item = SacolaItem::where('sacola_id', $sacola->id)
            ->where('id', $itemId)
            ->first();
        
        if(!$item) {
            return response()->json(['error' => 'Produto não encontrado!', 200]);
        }

        $item->increment('quantidade', $quantia);

        $sacola->load(['itens.produto']);

        return response()->json($sacola, 200);
    }
}
