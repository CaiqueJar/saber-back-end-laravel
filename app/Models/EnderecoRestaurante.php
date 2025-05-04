<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnderecoRestaurante extends Model
{
    use SoftDeletes;
    
    protected $table = 'restaurante_endereco';

    protected $fillable = [
        'id',
        'restaurante_id',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'criado_em',
        'atualizado_em',
        'deletado_em',
    ];

    protected $dates = ['criado_em', 'atualizado_em', 'deletado_em'];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'deletado_em';

    public function restaurante(): belongsTo
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id', 'id');
    }
}
