<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sacola extends Model
{
    protected $table = 'sacola';

    protected $fillable = [
        'id',
        'restaurante_id',
        'usuario_id',
        'valor_total_parcial',
        'criado_em ',
        'atualizado_em ',
        'deletado_em ',
    ];

    protected $dates = ['criado_em', 'atualizado_em', 'deletado_em'];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'deletado_em';
    

    public function itens(): HasMany
    {
        return $this->hasMany(SacolaItem::class);
    }

    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }
}
