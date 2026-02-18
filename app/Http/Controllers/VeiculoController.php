<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVeiculoRequest;
use App\Http\Requests\UpdateVeiculoRequest;
use App\Http\Resources\VeiculoResource;
use App\Jobs\DeleteVeiculosJob;
use App\Models\Veiculos;
use Exception;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $currentPage = $request->get('current_page') ?? 1;
        $regsPerPage = 10;

        $skip = ($currentPage - 1) * $regsPerPage; // 1 = 0 -- 2 = 3

        $veiculos = Veiculos::withTrashed()->skip($skip)->take($regsPerPage)->orderByDesc('id')->get();

        return VeiculoResource::collection($veiculos);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreVeiculoRequest $request)
    {
        $data = $request->validated();

        try{
            $veiculos = Veiculos::create($data);

            return (new VeiculoResource($veiculos))
                ->response()
                ->setStatusCode(201);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'Falha ao inserir veiculo',
                'error' => $ex->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $motorista = Veiculos::findOrFail($id);
            return new VeiculoResource($motorista);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'Falha ao buscar veiculo'
            ], 404);
        }
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVeiculoRequest $request, string $id)
    {
        $data = $request->validated();

        try{
            $veiculo = Veiculos::findOrFail($id);
            $veiculo->update($data);
            return response()->json($veiculo, 200);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'Falha ao alterar veiculo'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DeleteVeiculosJob::dispatch($id)->delay(now()->plus(seconds: 5))->onQueue('deleteVeiculos');
            return response()->json(null, 204);
        } catch(\Exception){
            return response()->json([
                'message' => 'Falha ao remover veiculo'
            ], 400);
        }
    }
}
