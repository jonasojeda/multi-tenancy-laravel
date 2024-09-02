<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class SubDomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Crear
     */
    public function store(Request $request)
    {
        $validates = [
            'subDomain' => 'required|string',
        ];
        $centralDomain = env('CENTRAL_DOMAIN');
        $tenant1 = Tenant::create();
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
