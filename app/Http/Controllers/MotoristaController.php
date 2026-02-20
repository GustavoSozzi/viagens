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

    public function importar(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('motoristas');

        $handle = fopen($file->getRealPath(), 'r');

        $erros = [];
        $salvos = 0;
        $linhaNumero = 0;

        DB::beginTransaction();

        try {

            while (($linha = fgetcsv($handle, 1000, ",")) !== false) {

                $linhaNumero++;

                if ($linhaNumero == 1) {
                    continue;
                }

                $nome = trim($linha[0] ?? '');
                $dataNascimento = trim($linha[1] ?? '');
                $numeroCnh = trim($linha[2] ?? '');

                $erroLinha = [];

                try {
                    $data = Carbon::createFromFormat('d-m-Y', $dataNascimento);
                    if ($data->age < 18) {
                        $erroLinha[] = "Não é permitido motorista menor de 18 anos";
                    }
                } catch (\Exception $e) {
                    $erroLinha[] = "Data no formato inválida";
                }

                if (!ctype_digit($numeroCnh) || strlen($numeroCnh) != 11) {
                    $erroLinha[] = "CNH deve ter 11 dígitos numéricos";
                }

                if (!empty($erroLinha)) {
                    $erros[] = [
                        'linha' => $linhaNumero,
                        'nome' => $nome,
                        'erros' => $erroLinha
                    ];
                    continue;
                }

                Motorista::create([
                    'nome' => $nome,
                    'data_nascimento' => $data->format('Y-m-d'),
                    'numero_cnh' => $numeroCnh
                ]);

                $salvos++;
            }

            fclose($handle);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'erro_geral' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'total_salvos' => $salvos,
            'total_erros' => count($erros),
            'erros' => $erros
        ]);
    }

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
        $input = $request->input();
        $client_id = $request->input('client_id');

        if (Input::hasFile('name'))
        {
            $file = Input::file('name');
            $name = time() . '-' . $file->getClientOriginalName();
            $path = storage_path('documents');

            Lists::create(['client_id' => $client_id, 'name' => $name]);

            $reader = Reader::createFromPath($file->getRealPath());
            // Create a customer from each row in the CSV file
            $headers = array();

            foreach ($reader as $index => $row)
            {
                if ($index === 0)
                {
                    $headers = $row;
                } else
                {
                    $data = array_combine($headers, $row);
                    Customers::create($data);
                }
            }

            $file->move($path, $name);

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
