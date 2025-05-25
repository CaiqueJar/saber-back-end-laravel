<?php

namespace App\Http\Controllers;

use App\Models\CategoriaRestaurante;
use Illuminate\Http\Request;

class CategoriaRestauranteController extends Controller
{
    private CategoriaRestaurante $categoriaRestaurante;

    public function __construct(CategoriaRestaurante $categoriaRestaurante)
    {
        $this->categoriaRestaurante = $categoriaRestaurante;
    }

    public function list(int $limit = 0)
    {
        if ($limit > 0) {
            return $this->categoriaRestaurante->limit($limit)->get();
        }

        return $this->categoriaRestaurante->all();

    }
}
