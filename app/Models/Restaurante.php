<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Restaurante extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    protected $table = 'restaurante';

    protected $fillable = [
        'id',
        'nome_completo_login',
        'email',
        'senha',
        'celular',
        'cnpj',
        'razao_social',
        'nome_loja',
        'telefone_celular',
        'categoria_id',
        'logo',
        'banner',
        'descricao',
        'pedido_minimo',
        'taxa_entrega',
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
        return $this->belongsTo(CategoriaRestaurante::class, 'categoria_id');
    }

    public function endereco(): HasOne
    {
        return $this->hasOne(EnderecoRestaurante::class, 'restaurante_id', 'id');
    }

    public function horarioFuncionamento(): HasMany
    {
        return $this->hasMany(HorarioFuncionamento::class, 'restaurante_id', 'id');
    }

    public function categoriaProdutos(): HasMany
    {
        return $this->hasMany(CategoriaProduto::class, 'restaurante_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }
}
