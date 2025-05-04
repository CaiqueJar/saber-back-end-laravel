<?php

namespace App\Http\Controllers;

use App\Http\Requests\HorarioFuncionamentoRequest;
use App\Http\Requests\HorarioFuncionamentoUpdateRequest;
use App\Models\HorarioFuncionamento;
use Illuminate\Http\Request;

class HorarioFuncionamentoController extends Controller
{

    private HorarioFuncionamento $horarioFuncionamento;

    public function __construct(HorarioFuncionamento $horarioFuncionamento)
    {
        $this->horarioFuncionamento = $horarioFuncionamento;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->horarioFuncionamento->all();
    }

    public function listByRestaurant(string $restauranteId)
    {
        $horarios = $this->horarioFuncionamento->where('restaurante_id', $restauranteId)->get();

        if ($horarios->isEmpty()) {
            return response()->json([
                'error' => 'Nenhum horário de funcionamento encontrado para este restaurante.',
            ], 200);
        }

        return response()->json($horarios, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HorarioFuncionamentoRequest $request)
    {
        $data = $request->validated();

        $dataJaExistente = $this->horarioFuncionamento->where([
            'restaurante_id' => $data['restaurante_id'],
            'dia_semana' => $data['dia_semana'],
        ])->first();

        if ($dataJaExistente) {
            return response()->json([
                'error' => 'Horário de funcionamento já cadastrado para este dia da semana.',
            ], 200);
        }

        $horarioFuncionamento = $this->horarioFuncionamento->create($data);
        return response()->json($horarioFuncionamento, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $horarioFuncionamento = $this->horarioFuncionamento->find($id);

        if (!$horarioFuncionamento) {
            return response()->json([
                'error' => 'Horário de funcionamento não encontrado.',
            ], 200);
        }

        return response()->json($horarioFuncionamento, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HorarioFuncionamentoUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        $horarioFuncionamento = $this->horarioFuncionamento->find($id);
        if (!$horarioFuncionamento) {
            return response()->json([
                'error' => 'Horário de funcionamento não encontrado.',
            ], 200);
        }

        $dataJaExistente = $this->horarioFuncionamento->where([
            'restaurante_id' => $data['restaurante_id'],
            'dia_semana' => $data['dia_semana'],
        ])->first();

        if ($dataJaExistente && $dataJaExistente->id !== $horarioFuncionamento->id) {
            return response()->json([
                'error' => 'Horário de funcionamento já cadastrado para este dia da semana.',
            ], 200);
        }

        $horarioFuncionamento->update($data);
        return response()->json($horarioFuncionamento, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $horarioFuncionamento = $this->horarioFuncionamento->find($id);
        if (!$horarioFuncionamento) {
            return response()->json([
                'error' => 'Horário de funcionamento não encontrado.',
            ], 200);
        }

        $horarioFuncionamento->delete();
        return response()->json([], 204);
    }
}
