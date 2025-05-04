<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Http\Requests\ProdutoUpdateRequest;
use App\Models\CategoriaProduto;
use App\Models\Produto;
use App\Models\Restaurante;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    private Produto $produto;

    public function __construct(Produto $produto)
    {
        $this->produto = $produto;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->produto->all();
    }

    public function listByRestaurant(string $restauranteId)
    {
        $restaurante = Restaurante::find($restauranteId);
        if (!$restaurante) {
            return response()->json(['error' => 'Restaurante com id ' . $restauranteId . ' não encontrado'], 200);
        }

        $produtos = $this->produto->join('categoria_produto', 'produto.categoria_id', '=', 'categoria_produto.id')
            ->where('categoria_produto.restaurante_id', $restauranteId)
            ->select('produto.*')
            ->get();

        return $produtos;
    }
    public function listByCategory(string $categoriaId)
    {
        $categoriaProduto = CategoriaProduto::find($categoriaId);
        if (!$categoriaProduto) {
            return response()->json(['error' => 'Categoria de produto com id ' . $categoriaId . ' não encontrado'], 200);
        }

        return $this->produto->where('categoria_id', $categoriaId)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdutoRequest $request)
    {
        $data = $request->validated();

        $categoriaProduto = CategoriaProduto::find($data['categoria_id']);
        if (!$categoriaProduto) {
            return response()->json(['error' => 'Categoria de produto com id ' . $data['categoria_id'] . ' não encontrado'], 200);
        }

        return response()->json($this->produto->create($data), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produto = $this->produto->find($id);

        if (!$produto) {
            return response()->json(['error' => 'Produto com id ' . $id . ' não encontrado'], 200);
        }

        return response()->json($produto, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProdutoUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        $produto = $this->produto->find($id);
        if (!$produto) {
            return response()->json(['error' => 'Produto com id ' . $id . ' não encontrado'], 200);
        }

        $produto->update($data);

        return response()->json($produto, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produto = $this->produto->find($id);

        if (!$produto) {
            return response()->json(['error' => 'Produto com id ' . $id . ' não encontrado'], 200);
        }

        $produto->delete();

        return response()->json([], 204);
    }

    /**
     * Restore a deleted row.
     */
    public function restore(string $id)
    {
        $produto = $this->produto->withTrashed()->find($id);

        if (!$produto) {
            return response()->json(['error' => 'Produto com id ' . $id . ' não encontrado'], 404);
        }

        $produto->restore();

        return response()->json([], 204);
    }
}
