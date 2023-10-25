<?php

namespace App\Http\Controllers;

use App\Models\musim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MusimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all data
        $musim = musim::all();

        // respon api
        return response()->json([
            'success' => true,
            'message' => 'Daftar data musim',
            'data' => $musim
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // validate request
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()], 422);
        }

        // create musim
        $musim = musim::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        // response api
        return response()->json([
            'success' => true,
            'message' => 'Musim created successfully',
            'data' => $musim
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //find musim
        $musim = musim::find($id);

        // if musim not exist
        if (!$musim) {
            return response()->json([
                'success' => false,
                'message' => 'Musim not found',
            ], 404);
        }

        // response api
        return response()->json([
            'success' => true,
            'message' => 'Detail musim',
            'data' => $musim
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // find musim
        $musim = musim::find($id);

        // if musim not exist
        if (!$musim) {
            return response()->json([
                'success' => false,
                'message' => 'Musim not found',
            ], 404);
        }

        // validate request
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()], 422);
        }

        // update musim
        $musim->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        // response api
        return response()->json([
            'success' => true,
            'message' => 'Musim updated successfully',
            'data' => $musim
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // find
        $musim = musim::find($id);

        // if musim not exist
        if (!$musim) {
            return response()->json([
                'success' => false,
                'message' => 'Musim not found',
            ], 404);
        }

        // delete musim
        $musim->delete();

        // response api
        return response()->json([
            'success' => true,
            'message' => 'Musim deleted successfully',
        ], 200);
    }
}
