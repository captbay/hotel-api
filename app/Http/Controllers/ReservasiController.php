<?php

namespace App\Http\Controllers;

use App\Models\reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get
        $reservasis = reservasi::with(
            'customer',
            'pegawai',
            'transaksi_kamar',
            'transaksi_fasilitas_tambahan'
        )
            ->get();

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Daftar data reservasi',
            'data' => $reservasis
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function indexCustomer()
    {
        // find customer id by auth user
        $customer_id = Auth::user()->customer->id;

        // if customer id null
        if (!$customer_id) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Data customer tidak ditemukan',
            ], 404);
        }

        //get
        $reservasis = reservasi::with(
            'customer',
            'pegawai',
            'transaksi_kamar',
            'transaksi_fasilitas_tambahan'
        )
            ->where('customer_id', $customer_id)
            ->get();

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Daftar data reservasi',
            'data' => $reservasis
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //find
        $reservasi = reservasi::with(
            'customer',
            'pegawai',
            'transaksi_kamar',
            'transaksi_fasilitas_tambahan'
        )
            ->find($id);

        //if data reservasi null
        if (!$reservasi) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Data reservasi tidak ditemukan',
            ], 404);
        }

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Detail data reservasi',
            'data' => $reservasi
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
