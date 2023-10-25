<?php

namespace App\Http\Controllers;

use App\Models\jenis_kamar;
use App\Http\Requests\Storejenis_kamarRequest;
use App\Http\Requests\Updatejenis_kamarRequest;

class JenisKamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all jenis kamar
        $jenis_kamar = jenis_kamar::all();

        // respon api
        return response()->json([
            'success' => true,
            'message' => 'Daftar data jenis kamar',
            'data' => $jenis_kamar
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(jenis_kamar $jenis_kamar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatejenis_kamarRequest $request, jenis_kamar $jenis_kamar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(jenis_kamar $jenis_kamar)
    {
        //
    }
}
