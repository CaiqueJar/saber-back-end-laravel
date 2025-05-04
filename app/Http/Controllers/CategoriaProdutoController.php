<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaProdutoRequest;
use App\Models\CategoriaProduto;
use App\Models\Restaurante;
use Illuminate\Http\Request;

class CategoriaProdutoController extends Controller
{

    private CategoriaProduto $categoriaProduto;

    public function __construct(CategoriaProduto $categoriaProduto)
    {
        $this->categoriaProduto = $categoriaProduto;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->categoriaProduto->all();
    }

    public function listByRestaurant(string $restauranteId)
    {
        $restaurante = Restaurante::find($restauranteId);
        if (!$restaurante) {
            return response()->json(['error' => 'Restaurante com id ' . $restauranteId . ' não encontrado'], 404);
        }
        return $this->categoriaProduto->with('produtos')->where('restaurante_id', $restauranteId)->get();
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriaProdutoRequest $request)
    {
        $data = $request->validated();

        $restaurante = Restaurante::find($data['restaurante_id']);
        if (!$restaurante) {
            return response()->json(['error' => 'Restaurante com id ' . $data['restaurante_id'] . ' não encontrado'], 404);
        }

        $categoriaProduto = $this->categoriaProduto->create($data);

        return response()->json($categoriaProduto, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoriaProduto = $this->categoriaProduto->find($id);

        if (!$categoriaProduto) {
            return response()->json(['error' => 'Categoria de produto com id ' . $id . ' não encontrado'], 404);
        }

        return response()->json($categoriaProduto, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoriaProduto = $this->categoriaProduto->find($id);

        if (!$categoriaProduto) {
            return response()->json(['error' => 'Categoria de produto com id ' . $id . ' não encontrado'], 404);
        }

        return response()->json($categoriaProduto->delete(), 204);
    }
}
