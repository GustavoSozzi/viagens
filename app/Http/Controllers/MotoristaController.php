<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMotoristaRequest;
use App\Http\Requests\UpdateMotoristaRequest;
use App\Http\Resources\MotoristaResource;
use App\Jobs\DeleteMotoristasJob;
use App\Models\Motoristas;
use Exception;
use Illuminate\Http\Request;

class MotoristaController extends Controller
{
     /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $currentPage = $request->get('current_page') ?? 1;
        $regsPerPage = 10;

        $skip = ($currentPage - 1) * $regsPerPage; // 1 = 0 -- 2 = 3

        $motoristas = Motoristas::withTrashed()->skip($skip)->take($regsPerPage)->orderByDesc('id')->get();

        return MotoristaResource::collection($motoristas);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreMotoristaRequest $request)
    {
        $data = $request->validated();

        try{
            $motorista = Motoristas::create($data);

            return (new MotoristaResource($motorista))
                ->response()
                ->setStatusCode(201);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'Falha ao inserir motorista',
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
            $motorista = Motoristas::findOrFail($id);
            return new MotoristaResource($motorista);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'Falha ao buscar motorista'
            ], 404);
        }
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMotoristaRequest $request, string $id)
    {
        $data = $request->validated();

        try{
            $motorista = Motoristas::findOrFail($id);
            $motorista->update($data);

            return response()->json($motorista, 200);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'Falha ao alterar motorista'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DeleteMotoristasJob::dispatch($id)->delay(now()->plus(seconds: 5))->onQueue('deleteMotoristas');
            return response()->json(null, 204);
        } catch(\Exception){
            return response()->json([
                'message' => 'Falha ao remover motorista'
            ], 400);
        }
    }
}
