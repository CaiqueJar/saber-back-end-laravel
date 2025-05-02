<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdutoRequest $request)
    {
        $data = $request->validated();

        $categoriaProduto = CategoriaProduto::find($data['categoria_id']);
        if (!$categoriaProduto) {
            return response()->json(['error' => 'Categoria de produto com id ' . $data['categoria_id'] . ' não encontrado'], 404);
        }

        return response()->json($this->produto->create($data), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        //
    }
}
