<?php

namespace App\Http\Controllers;

use App\Http\Clases\App;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group Subdominios
 * 
 */
class SubDomainController extends Controller
{

    // public function __construct()
    // {
    //     //control de los permisos
    //     $this->authorizeResource(Cliente::class, 'subDominio');
    // }

    /**
     * Obtener todos
     * 
     * @queryParam subDomain Example: dominio1
     * @queryParam order_desc Example: true
     * @queryParam order_asc Example: true
     * @queryParam sinPaginar Example: true
     * @queryParam nroPagina Example: 1
     */
    public function index(Request $request)
    {
        //Query parameters
        $validated = $request->validate([
            //Example: nombre subdominio
            'subDomain' => 'sometimes|string',
            //Example: true
            'order_desc' => 'sometimes|boolean',
            //Example: true
            'order_asc' => 'sometimes|boolean',
            //Example: true
            'sinPaginar' => 'sometimes|boolean',
            //Example: 1
            'nroPagina' => 'sometimes|integer',
        ]);

        //Parametros
        $subDomain = request()->query('subDomain');
        $orderDesc = request()->has('order_desc') ? request()->query('order_desc') : true;
        $orderAsc = request()->has('order_asc') ? request()->has('order_asc') : false;
        $sinPaginar = request()->query('sinPaginar');
        $nroPagina = request()->has('nroPagina') ? request()->query('nroPagina') : 1;

        $dBlist = Tenant::when($subDomain, function ($query, $subDomain) {
            $query->where('id', 'LIKE', "%$subDomain%");
        })
            ->when($orderDesc, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->when($orderAsc, function ($query) {
                $query->orderBy('created_at', 'asc');
            });


        if ($sinPaginar) {
            $dBList = $dBlist->get();
        } else {
            $dBList = $dBlist->paginate(App::CANTIDAD_ITEMS_DEVOLVER_POR_PAGINA, ['*'], 'page', $nroPagina);
        }

        $listaDevolver = collect();
        if ($dBList) {
            foreach ($dBList as $item) {
                $listaDevolver->push($item->obtenerDatos());
            }
        }

        //Datos de paginado
        $pagTotalItems = $sinPaginar == true  ? count($dBList) : $dBList->total();
        $pagTotal = $sinPaginar == true  ? 1 : ceil($pagTotalItems / App::CANTIDAD_ITEMS_DEVOLVER_POR_PAGINA);
        $pagActual = $nroPagina;

        return response()->json([
            'data' => $listaDevolver,
            'current_page' => (int) $pagActual,
            'last_page' => $pagTotal,
            'total' => $pagTotalItems
        ], 200);
    }

    /**
     * Crear
     */
    public function store(Request $request)
    {
        $validates = [
            'subDomain' => 'required|string|unique:tenants,id',
        ];

        $validator = Validator::make($request->input(), $validates);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
        
        $centralDomain = env('CENTRAL_DOMAIN');
        $tenant1 = Tenant::create(['id' => $request->subDomain]);
        $tenant1->domains()->create(['domain' => "$request->subDomain.$centralDomain"]);

        return response()->json([
            'status' => true,
            'data' => $tenant1->obtenerDatos(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
