<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    protected $table = 'usuario';

    protected $fillable = [
        'id',
        'google_id',
        'facebook_id',
        'token',
        'nome_completo',
        'email',
        'cpf' ,
        'telefone_celular',
        'criado_em',
        'atualizado_em',
        'deletado_em',
    ];

    protected $dates = ['criado_em', 'atualizado_em', 'deletado_em'];

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'deletado_em';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function enderecos(): HasMany
    {
        return $this->hasMany(EnderecoUsuario::class, 'usuario_id', 'id');
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'usuario_id', 'id');
    }
}
