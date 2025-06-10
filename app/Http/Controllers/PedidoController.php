<?php

namespace App\Http\Controllers;

use App\Mail\NotaFiscalMail;
use App\Models\EnderecoUsuario;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Sacola;
use App\Models\SacolaItem;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PedidoController extends Controller
{
    public function criarPedido(Request $request)
    {
        // Validação básica
        $request->validate([
            'usuario_id' => 'required|integer|exists:usuario,id',
            'sacola_id' => 'required|integer|exists:sacola,id',
            'endereco_entrega' => 'required|array',
            'endereco_entrega.rua' => 'required|string',
            'endereco_entrega.numero' => 'required|string',
            'endereco_entrega.bairro' => 'required|string',
            'endereco_entrega.cidade' => 'required|string',
            'endereco_entrega.estado' => 'required|string|size:2',
            'endereco_entrega.cep' => 'required|string',
            'observacao' => 'nullable|string',
        ]);

        $data = $request->all();

        // Busca a sacola com itens
        $sacola = Sacola::with('itens.produto')->find($data['sacola_id']);
        
        if (!$sacola) {
            return response()->json(['error' => 'Sacola não encontrada'], 404);
        }

        // Verifica se a sacola pertence ao usuário
        if ($sacola->usuario_id != $data['usuario_id']) {
            return response()->json(['error' => 'Esta sacola não pertence ao usuário'], 403);
        }

        // Verifica se a sacola tem itens
        if ($sacola->itens->isEmpty()) {
            return response()->json(['error' => 'Sacola vazia'], 400);
        }

        // Trata o endereço de entrega
        $endereco = $this->processarEndereco($data['usuario_id'], $data['endereco_entrega']);

        // Calcula totais
        $valorProdutos = $sacola->itens->sum(function ($item) {
            return $item->quantidade * $item->preco_unitario;
        });

        $taxaEntrega = $sacola->restaurante->taxa_entrega ?? 0;
        $valorTotal = $valorProdutos + $taxaEntrega;

        // Cria o pedido
        $pedido = Pedido::create([
            'codigo' => 'PED' . Str::upper(Str::random(6)),
            'usuario_id' => $data['usuario_id'],
            'restaurante_id' => $sacola->restaurante_id,
            'endereco_entrega_id' => $endereco->id,
            'status' => Pedido::STATUS_PENDENTE,
            'valor_produtos' => $valorProdutos,
            'taxa_entrega' => $taxaEntrega,
            'valor_total' => $valorTotal,
            'forma_pagamento' => 'pix',
            'troco_para' => $data['troco_para'] ?? null,
            'observacao' => $data['observacao'] ?? null,
        ]);

        // Adiciona os itens do pedido
        foreach ($sacola->itens as $itemSacola) {
            PedidoItem::create([
                'pedido_id' => $pedido->id,
                'produto_id' => $itemSacola->produto_id,
                'quantidade' => $itemSacola->quantidade,
                'preco_unitario' => $itemSacola->preco_unitario,
            ]);
        }

        // Limpa a sacola após criar o pedido
        SacolaItem::where('sacola_id', $sacola->id)->delete();
        $sacola->delete();

        $usuario = Usuario::find($data['usuario_id']);
        Mail::to($usuario->email)->send(new NotaFiscalMail($pedido));

        return response()->json([
            'message' => 'Pedido criado com sucesso',
            'pedido' => $pedido->load('itens.produto', 'enderecoEntrega', 'restaurante')
        ], 201);
    }

    private function processarEndereco($usuarioId, $dadosEndereco)
    {
        if (isset($dadosEndereco['id'])) {
            $endereco = EnderecoUsuario::where('id', $dadosEndereco['id'])
                ->where('usuario_id', $usuarioId)
                ->first();
            
            if (!$endereco) {
                throw new \Exception('Endereço não encontrado ou não pertence ao usuário');
            }
            
            return $endereco;
        }

        return EnderecoUsuario::create([
            'usuario_id' => $usuarioId,
            'cep' => preg_replace('/[^0-9]/', '', $dadosEndereco['cep']),
            'rua' => $dadosEndereco['rua'],
            'numero' => $dadosEndereco['numero'],
            'complemento' => $dadosEndereco['complemento'] ?? null,
            'bairro' => $dadosEndereco['bairro'],
            'cidade' => $dadosEndereco['cidade'],
            'estado' => $dadosEndereco['estado'],
            'ponto_referencia' => $dadosEndereco['ponto_referencia'] ?? null,
        ]);
    }

    public function atualizarPedido(Request $request, string $id)
    {
        $pedido = Pedido::find($id);

        $pedido->update([
            'status' => $request->status,
        ]);

        return response()->json($pedido);
    }
}