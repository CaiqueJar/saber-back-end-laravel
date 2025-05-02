<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaProduto extends Model
{
    protected $table = 'categoria_produto';

    protected $fillable = [
        'id',
        'restaurante_id',
        'nome',
        'ordem',
        'criado_em',
        'atualizado_em',
        'deletado_em',
    ];

    protected $dates = ['criado_em', 'atualizado_em', 'deletado_em'];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'deletado_em';

    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class, 'categoria_id');
    }
}
