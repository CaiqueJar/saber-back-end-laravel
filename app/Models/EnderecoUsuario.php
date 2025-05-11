<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnderecoUsuario extends Model
{
    protected $table = "endereco";

    protected $fillable = [
        'id',
        'usuario_id',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'ponto_referencia',
        'criado_em',
        'atualizado_em',
        'deletado_em',
    ];

    protected $dates = ['criado_em', 'atualizado_em', 'deletado_em'];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'deletado_em';

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
