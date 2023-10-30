<?php

namespace App\Http\Controllers;

use App\Models\kamar;
use Carbon\Carbon;
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
     * Display a listing of the resource.
     */
    public function information()
    {
        // get data kamar by musim where start_date is date now
        // $kamars = kamar::with('jenis_kamar.tarif_musim.musim')->get();
        $kamars = kamar::with(['jenis_kamar' => function ($query) {
            $query->select('id', 'name', 'bed', 'total_bed', 'luas_kamar', 'harga_default')
                ->with(['tarif_musim' => function ($query) {
                    $query->select('id', 'jenis_kamar_id', 'musim_id', 'harga')
                        ->with(['musim' => function ($query) {
                            $query->select('id', 'name', 'start_date', 'end_date');
                        }])
                        ->whereHas('musim', function ($query) {
                            $query->whereDate('start_date', '<=', Carbon::now()->format('Y-m-d'))
                                ->WhereDate('end_date', '>=', Carbon::now()->format('Y-m-d'));
                        });
                }]);
        }])->get();

        // map data to check if musim start_date is date now
        $data = $kamars->map(function ($item, $key) {
            // no kamar
            $item->no_kamar = $item->no_kamar;
            // status kamar
            $item->status = $item->status;
            // nama kamar
            $item->nama_kamar = $item->jenis_kamar->name;
            // tipe bed
            $item->tipe_bed = $item->jenis_kamar->bed;
            // total bed
            $item->total_bed = $item->jenis_kamar->total_bed;
            // luas kamar
            $item->luas_kamar = $item->jenis_kamar->luas_kamar;

            // if $item->jenis_kamar->tarif_musim = []
            if (count($item->jenis_kamar->tarif_musim) == 0) {
                // harga
                $item->harga = $item->jenis_kamar->harga_default;
            } else {
                // harga
                $item->harga = $item->jenis_kamar->tarif_musim[0]->harga;
            }

            return $item;
        });

        // unset useless data
        $data = $data->map(function ($item, $key) {
            unset($item->id);
            unset($item->jenis_kamar_id);
            unset($item->created_at);
            unset($item->updated_at);
            unset($item->jenis_kamar);

            return $item;
        });

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Daftar data kamar',
            'data' => $data
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
        $kamars = kamar::get();

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

        // generate no_kamar (format K1)
        $final_no_kamar = 'K' . ($count + 1);
        // jika belom ada kamar
        if ($count == 0) {
            $final_no_kamar = 'K1';
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
