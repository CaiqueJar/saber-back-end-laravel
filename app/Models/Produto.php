<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produto extends Model
{
    protected $table = 'produto';

    protected $fillable = [
        'id',
        'categoria_id',
        'nome',
        'descritivo',
        'imagem',
        'preco',
        'disponibilidade',
        'desconto',
        'status',
        'criado_em',
        'atualizado_em',
        'deletado_em',
    ];

    protected $dates = ['criado_em', 'atualizado_em', 'deletado_em'];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'deletado_em';

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaProduto::class, 'categoria_id', 'id');
    }
}
