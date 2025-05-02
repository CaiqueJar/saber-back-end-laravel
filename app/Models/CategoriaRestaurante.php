<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaRestaurante extends Model
{
    use SoftDeletes;

    protected $table = 'categoria_restaurante';

    protected $fillable = [
        'id',
        'nome',
        'cor_fundo_hex',
        'imagem',
        'ordem',
        'criado_em',
        'atualizado_em',
        'deletado_em',
    ];

    protected $dates = ['criado_em', 'atualizado_em', 'deletado_em'];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'deletado_em';

    public function restaurantes(): HasMany
    {
        return $this->hasMany(CategoriaRestaurante::class, 'categoria_id', 'id');
    }
}
