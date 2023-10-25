<?php

namespace App\Http\Controllers;

use App\Models\tarif_musim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class TarifMusimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get data
        $tarif_musim = tarif_musim::with('jenis_kamar', 'musim')->get();

        // response
        return response()->json([
            'success' => true,
            'message' => 'Daftar data tarif musim',
            'data' => $tarif_musim
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //validate request
        $validatedData = Validator::make($request->all(), [
            'musim_id' => 'required|integer',
            'jenis_kamar_id' => 'required|integer',
            'harga' => 'required|integer'
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()], 422);
        }

        //create tarif musim
        $tarif_musim = tarif_musim::create([
            'musim_id' => $request->musim_id,
            'jenis_kamar_id' => $request->jenis_kamar_id,
            'harga' => $request->harga
        ]);

        //response api
        return response()->json([
            'success' => true,
            'message' => 'Tarif musim created successfully',
            'data' => $tarif_musim
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //find 
        $tarif_musim = tarif_musim::with('jenis_kamar', 'musim')->find($id);

        //if not found
        if (!$tarif_musim) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, tarif musim with id ' . $id . ' cannot be found'
            ], 400);
        }

        //if found
        return response()->json([
            'success' => true,
            'message' => 'Detail data tarif musim',
            'data' => $tarif_musim
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //find
        $tarif_musim = tarif_musim::with('jenis_kamar', 'musim')->find($id);

        //if not found
        if (!$tarif_musim) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, tarif musim with id ' . $id . ' cannot be found'
            ], 400);
        }

        //validate request
        $validatedData = Validator::make($request->all(), [
            'musim_id' => 'required|integer',
            'jenis_kamar_id' => 'required|integer',
            'harga' => 'required|integer'
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()], 422);
        }

        //update tarif musim
        $tarif_musim->update([
            'musim_id' => $request->musim_id,
            'jenis_kamar_id' => $request->jenis_kamar_id,
            'harga' => $request->harga
        ]);

        //response
        return response()->json([
            'success' => true,
            'message' => 'Tarif musim updated successfully',
            'data' => $tarif_musim
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //find
        $tarif_musim = tarif_musim::find($id);

        //if not found
        if (!$tarif_musim) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, tarif musim with id ' . $id . ' cannot be found'
            ], 400);
        }

        //if found
        $tarif_musim->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarif musim deleted successfully'
        ], 200);
    }
}
