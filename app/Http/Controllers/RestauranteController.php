<?php

namespace App\Http\Controllers;

use App\Http\Requests\RestauranteRequest;
use App\Http\Requests\RestauranteUpdateRequest;
use App\Models\EnderecoRestaurante;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class RestauranteController extends Controller
{

    private $restaurante;
    private $endereco;

    public function __construct(Restaurante $restaurante, EnderecoRestaurante $endereco)
    {
        $this->restaurante = $restaurante;
        $this->endereco = $endereco;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->restaurante->with('categoria')->get();
    }

    public function getEndereco(string $id)
    {
        $restaurante = $this->restaurante->with('endereco')->find($id);

        if (!$restaurante) {
            return response()->json(['error' => 'Restaurante com id ' . $id . ' não encontrado'], 404);
        }

        return response()->json($restaurante->endereco, 200);
    }

    /**
     * Display a listing of the trashed resource.
     */
    public function listTrashed()
    {
        return $this->restaurante->onlyTrashed()->get();
    }

    /**
     * Display a search of resources.
     */
    public function search(Request $request)
    {
        $search = $request->input('pesquisa');

        $restaurantes = $this->restaurante->with('categoria')
            ->where('nome_loja', 'LIKE', "%{$search}%")
            ->get();
            
        return response()->json($restaurantes);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(RestauranteRequest $request)
    {

        $data = $request->validated();

        $restaurante = $this->restaurante->create([
            'nome_completo_login' => $data['nome_completo_login'],
            'email' => $data['email'],
            'senha' => Hash::make($data['senha']),
            'celular' => $data['celular'],
            'cnpj' => $data['cnpj'],
            'razao_social' => $data['razao_social'],
            'nome_loja' => $data['nome_loja'],
            'telefone_celular' => $data['telefone_celular'],
            'categoria_id' => $data['categoria_id'],
            'logo' => $data['logo'] ?? null,
            'banner' => $data['banner'] ?? null,
            'descricao' => $data['descricao'] ?? null,
            'pedido_minimo' => $data['pedido_minimo'] ?? null,
            'taxa_entrega' => $data['taxa_entrega'] ?? null,
        ]);

        $this->endereco->create([
            'restaurante_id' => $restaurante->id,
            'cep' => $data['cep'],
            'logradouro' => $data['logradouro'],
            'numero' => $data['numero'],
            'complemento' => $data['complemento'] ?? null,
            'bairro' => $data['bairro'],
            'cidade' => $data['cidade'],
            'estado' => $data['estado'],
        ]);

        $token = JWTAuth::fromUser($restaurante);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'restaurante' => $restaurante,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $restaurante = $this->restaurante->with(['categoria', 'endereco'])->find($id);

        if (!$restaurante) {
            return response()->json(['error' => 'Restaurante com id ' . $id . ' não encontrado'], 404);
        }

        return response()->json($restaurante, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestauranteUpdateRequest $request, string $id)
    {
        $data = $request->validated();
        
        $restaurante = $this->restaurante->find($id);
        $restaurante->update($data);

        if (!$restaurante) {
            return response()->json(['error' => 'Restaurante com id ' . $id . ' não encontrado'], 200);
        }
        
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            $image->storeAs('restaurante/logo', $imageName, 'public');
            
            $data['logo'] = asset('storage/restaurante/logo/' . $imageName);
        }

        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            $image->storeAs('restaurante/banner', $imageName, 'public');
            
            $data['banner'] = asset('storage/restaurante/banner/' . $imageName);
        }


        $endereco = isset($data['endereco']) ? $data['endereco'] : null;
        if ($endereco) {
            unset($data['endereco']);

            $enderecoRestaurante = $this->endereco->where('restaurante_id', $id)->first();
            if (!$enderecoRestaurante) {
                return response()->json(['error' => 'Endereço do restaurante não encontrado'], 200);
            }

            $enderecoRestaurante->update($endereco);

        }

        $restaurante->update($data);

        $restaurante->load('endereco');

        return response()->json($restaurante, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $restaurante = $this->restaurante->find($id);

        if (!$restaurante) {
            return response()->json(['error' => 'Restaurante com id ' . $id . ' não encontrado'], 404);
        }

        $restaurante->delete();

        return response()->json([], 204);
    }

    /**
     * Restore a deleted row.
     */
    public function restore(string $id)
    {
        $restaurante = $this->restaurante->withTrashed()->find($id);

        if (!$restaurante) {
            return response()->json(['error' => 'Restaurante com id ' . $id . ' não encontrado'], 404);
        }

        $restaurante->restore();

        return response()->json([], 204);
    }

    public function checkIfEmailExists(Request $request)
    {
        $email = $request->input('email');

        if ($this->restaurante->where('email', $email)->exists()) {
            return response()->json(['exists' => true], 200);
        }

        return response()->json(['exists' => false], 200);
    }
}
