<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Restaurante;
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

        // Primeiro verificar se o restaurante está aberto
        $diasSemana = [
            'Sunday'    => 'domingo',
            'Monday'    => 'segunda',
            'Tuesday'   => 'terca',
            'Wednesday' => 'quarta',
            'Thursday'  => 'quinta',
            'Friday'    => 'sexta',
            'Saturday'  => 'sabado'
        ];

        $diaSemanaIngles = now()->format('l');
        $diaSemanaAtual = $diasSemana[$diaSemanaIngles] ?? null;
        $horaAtual = now()->format('H:i:s');

        // Buscar o restaurante com seus horários de funcionamento
        $restaurante = Restaurante::with('horarioFuncionamento')->find($restauranteId);

        if (!$restaurante) {
            return response()->json(['error' => 'Restaurante não encontrado!'], 200);
        }

        $aberto = false;
        if ($restaurante->horarioFuncionamento) {
            foreach ($restaurante->horarioFuncionamento as $horario) {
                if ($horario->dia_semana === $diaSemanaAtual && 
                    $horario->hora_abertura <= $horaAtual && 
                    $horario->hora_fechamento >= $horaAtual) {
                    $aberto = true;
                    break;
                }
            }
        }

        if (!$aberto) {
            return response()->json(['error' => 'Restaurante fechado no momento!'], 200);
        }

        // Continua com o processo se o restaurante estiver aberto
        $produto = Produto::with('categoria')
            ->whereHas('categoria', function ($query) use ($restauranteId) {
                $query->where('restaurante_id', $restauranteId);
            })
            ->find($produtoId);
        
        if(!$produto) {
            return response()->json(['error' => 'Produto não encontrado!'], 200);
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
