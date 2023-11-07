<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // customer with user
        $customers = customer::with('user')->get();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Daftar data customer',
            'data' => $customers
        ], 200);
    }

    // get customer grup
    public function indexGrup()
    {
        // customer with user
        $customers = customer::with('user')->where('nama_insitusi', '!=', null)->get();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Daftar data customer grup',
            'data' => $customers
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //find
        $customer = customer::with('user')->findOrfail($id);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Detail data customer',
            'data' => $customer
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // find customer by id
        $customer = customer::find($id);

        // if not found
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Data customer tidak ditemukan',
            ], 404);
        }

        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'no_identitas' => 'required|string',
            'no_phone' => 'required|regex:/^(0)8[1-9][0-9]{6,10}$/',
            'address' => 'required|string',
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->all()], 422);
        }

        // check siapa yang user role
        if ($request->nama_insitusi != null || $request->nama_insitusi != '') {
            $nama_insitusi = $request->nama_insitusi;

            // update customer
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'no_identitas' => $request->no_identitas,
                'no_phone' => $request->no_phone,
                'nama_insitusi' => $nama_insitusi,
                'address' => $request->address,
            ]);

            // return
            return response()->json([
                'success' => true,
                'message' => 'Update customer grup successfully',
            ], 200);
        } else {
            // update customer
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'no_identitas' => $request->no_identitas,
                'no_phone' => $request->no_phone,
                'address' => $request->address,
            ]);

            // return
            return response()->json([
                'success' => true,
                'message' => 'Update data successfully',
            ], 200);
        }
    }
}
