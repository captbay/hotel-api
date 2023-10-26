<?php

namespace App\Http\Controllers;

use App\Models\kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// available,  unavailable

class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kamars = kamar::with('jenis_kamar')->get();

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Daftar data kamar',
            'data' => $kamars
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'jenis_kamar_id' => 'required',
            // 'no_kamar' => 'required',
            'status' => 'required|string'  // available,  unavailable
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->all()], 422);
        }

        // find data kamar
        $kamars = kamar::with('jenis_kamar')->get();

        // count data kamar
        $count = count($kamars);

        // if data kamar > 115
        if ($count >= 115) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Kamar sudah penuh',
            ], 200);
        }

        // generate no_kamar
        if ($count > 0) {
            // get last no_kamar
            $last_no_kamar = $kamars[$count - 1]->no_kamar;

            // get last no_kamar
            $no_kamar = (int) $last_no_kamar + 1;
            $final_no_kamar = 'K' . $no_kamar;
        } else {
            $no_kamar = 1;
            $final_no_kamar = 'K' . $no_kamar;
        }

        // data
        $data = [
            'jenis_kamar_id' => $request->jenis_kamar_id,
            'no_kamar' => $final_no_kamar,
            'status' => $request->status,
        ];

        // insert data
        $kamar = kamar::create($data);

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Kamar berhasil ditambahkan',
            'data' => $kamar
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // show data by id
        $kamar = kamar::with('jenis_kamar')->find($id);

        // if data null
        if ($kamar == null) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Data kamar tidak ditemukan',
            ], 200);
        }

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Detail data kamar',
            'data' => $kamar
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // update 
        $kamar = kamar::with('jenis_kamar')->find($id);

        // if data null
        if ($kamar == null) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Data kamar tidak ditemukan',
            ], 200);
        }

        // validate
        $validatedData = Validator::make($request->all(), [
            'jenis_kamar_id' => 'required',
            'status' => 'required|string'  // available, unavailable
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->all()], 422);
        }

        // update kamar
        $kamar->update([
            'jenis_kamar_id' => $request->jenis_kamar_id,
            'status' => $request->status,
        ]);

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Kamar berhasil diupdate',
            'data' => $kamar
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // delete
        $kamar = kamar::find($id);

        // if data null
        if ($kamar == null) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Data kamar tidak ditemukan',
            ], 200);
        }

        // delete data
        $kamar->delete();

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Kamar berhasil dihapus',
        ], 200);
    }
}
