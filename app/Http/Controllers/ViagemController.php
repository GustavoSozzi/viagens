<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreViagemRequest;
use App\Http\Requests\UpdateViagemRequest;
use App\Http\Resources\ViagemResource;
use App\Models\Viagens;
use Exception;
use Illuminate\Http\Request;

class ViagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentPage = $request->get('current_page') ?? 1;
        $regsPerPage = 10;

        $skip = ($currentPage - 1) * $regsPerPage;

        $viagens = Viagens::with(['veiculo', 'motoristas'])
            ->skip($skip)
            ->take($regsPerPage)
            ->orderByDesc('id')
            ->get();

        return ViagemResource::collection($viagens);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreViagemRequest $request)
    {
        $data = $request->validated();

        try {
            $viagem = Viagens::create([
                'veiculo_id' => $data['veiculo_id'],
                'km_inicial' => $data['km_inicial'],
                'km_final' => $data['km_final'] ?? null,
                'data_hora_inicial' => $data['data_hora_inicial'],
                'data_hora_final' => $data['data_hora_final'] ?? null,
            ]);

            $viagem->motoristas()->attach($data['motoristas']);

            $viagem->load(['veiculo', 'motoristas']);

            return (new ViagemResource($viagem))
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Falha ao inserir viagem',
                'error' => $ex->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $viagem = Viagens::with(['veiculo', 'motoristas'])->findOrFail($id);
            return new ViagemResource($viagem);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Falha ao buscar viagem'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateViagemRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $viagem = Viagens::findOrFail($id);
            
            $viagem->update([
                'veiculo_id' => $data['veiculo_id'],
                'km_inicial' => $data['km_inicial'],
                'km_final' => $data['km_final'] ?? null,
                'data_hora_inicial' => $data['data_hora_inicial'],
                'data_hora_final' => $data['data_hora_final'] ?? null,
            ]);

            // Sincronizar motoristas (remove os antigos e adiciona os novos)
            $viagem->motoristas()->sync($data['motoristas']);

            // Carregar relacionamentos para retornar
            $viagem->load(['veiculo', 'motoristas']);

            return new ViagemResource($viagem);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Falha ao alterar viagem',
                'error' => $ex->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $removed = Viagens::destroy($id);
            if (!$removed) {
                throw new Exception();
            }

            return response()->json(null, 204);
        } catch (\Exception) {
            return response()->json([
                'message' => 'Falha ao remover viagem'
            ], 400);
        }
    }
}
