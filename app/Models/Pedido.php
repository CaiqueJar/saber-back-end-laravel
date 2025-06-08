<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;

    protected $table = 'pedido';

    protected $fillable = [
        'id',
        'codigo', // Código único para exibir ao usuário (ex: IFD12345)
        'usuario_id',
        'restaurante_id',
        'endereco_entrega_id', // Pode ser null se for retirada
        'status', // Ex: 'pendente', 'preparando', 'a_caminho', 'entregue', 'cancelado'
        'valor_produtos',
        'taxa_entrega',
        'valor_total',
        'forma_pagamento', // Ex: 'cartao', 'pix', 'dinheiro'
        'observacao',
        'motivo_cancelamento',
        'tempo_estimado', // Em minutos
        'avaliacao', // 1-5
        'comentario_avaliacao',
        'criado_em',
        'atualizado_em',
        'deletado_em',
    ];

    protected $dates = ['criado_em', 'atualizado_em', 'deletado_em'];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'deletado_em';

    // Status possíveis
    const STATUS_PENDENTE = 'pendente';
    const STATUS_PREPARANDO = 'preparando';
    const STATUS_A_CAMINHO = 'a_caminho';
    const STATUS_ENTREGUE = 'entregue';
    const STATUS_CANCELADO = 'cancelado';

    // Formas de pagamento
    const PAGAMENTO_CARTAO = 'cartao';
    const PAGAMENTO_PIX = 'pix';
    const PAGAMENTO_DINHEIRO = 'dinheiro';

    public function itens(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function enderecoEntrega(): BelongsTo
    {
        return $this->belongsTo(EnderecoUsuario::class, 'endereco_entrega_id');
    }
    

    // public function pagamentos(): HasMany
    // {
    //     return $this->hasMany(Pagamento::class);
    // }
}