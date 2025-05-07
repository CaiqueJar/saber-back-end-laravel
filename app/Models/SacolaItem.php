<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SacolaItem extends Model
{
    protected $table = 'item_sacola';

    protected $fillable = [
        'id',
        'sacola_id',
        'produto_id',
        'observacoes',
        'quantidade',
        'preco_unitario',
        'criado_em ',
        'atualizado_em ',
        'deletado_em ',
    ];

    protected $dates = ['criado_em', 'atualizado_em', 'deletado_em'];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'deletado_em';

    public function sacola(): BelongsTo
    {
        return $this->belongsTo(Sacola::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
