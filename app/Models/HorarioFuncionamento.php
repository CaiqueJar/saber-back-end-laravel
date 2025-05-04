<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HorarioFuncionamento extends Model
{
    use SoftDeletes;

    protected $table = 'horarios_funcionamento';

    protected $fillable = [
        'restaurante_id',
        'dia_semana',
        'hora_abertura',
        'hora_fechamento',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }
}
