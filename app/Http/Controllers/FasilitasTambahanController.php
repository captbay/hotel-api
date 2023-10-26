<?php

namespace App\Http\Controllers;

use App\Models\fasilitas_tambahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FasilitasTambahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get the resource
        $fasilitas_tambahan = fasilitas_tambahan::all();

        //response api
        return response()->json([
            'success' => true,
            'message' => 'Daftar data fasilitas tambahan',
            'data' => $fasilitas_tambahan
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //validate request
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string',
            'harga' => 'required|integer'
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->all()], 422);
        }

        //create fasilitas tambahan
        $fasilitas_tambahan = fasilitas_tambahan::create([
            'name' => $request->name,
            'harga' => $request->harga
        ]);

        //response api
        return response()->json([
            'success' => true,
            'message' => 'Fasilitas tambahan created successfully',
            'data' => $fasilitas_tambahan
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //find
        $fasilitas_tambahan = fasilitas_tambahan::find($id);

        //if not found
        if (!$fasilitas_tambahan) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, fasilitas tambahan tidak ditemukan'
            ], 400);
        }

        //if found
        return response()->json([
            'success' => true,
            'message' => 'Detail data fasilitas tambahan',
            'data' => $fasilitas_tambahan
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // find
        $fasilitas_tambahan = fasilitas_tambahan::find($id);

        // if not found
        if (!$fasilitas_tambahan) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, fasilitas tambahan tidak ditemukan'
            ], 400);
        }

        // validate request
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string',
            'harga' => 'required|integer'
        ]);

        // response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->all()], 422);
        }

        // update fasilitas tambahan
        $fasilitas_tambahan->update([
            'name' => $request->name,
            'harga' => $request->harga
        ]);

        // response api
        return response()->json([
            'success' => true,
            'message' => 'Fasilitas tambahan updated successfully',
            'data' => $fasilitas_tambahan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //find
        $fasilitas_tambahan = fasilitas_tambahan::find($id);

        //if not found
        if (!$fasilitas_tambahan) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, fasilitas tambahan tidak ditemukan'
            ], 400);
        }

        //delete
        $fasilitas_tambahan->delete();

        //response
        return response()->json([
            'success' => true,
            'message' => 'Fasilitas tambahan deleted successfully'
        ], 200);
    }
}
