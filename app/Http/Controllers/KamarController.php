<?php

namespace App\Http\Controllers;

use App\Models\kamar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        }])
            ->where('status', 'available')
            ->get();

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
            // nama musim
            if (count($item->jenis_kamar->tarif_musim) == 0) {
                $item->nama_musim = 'tidak ada musim';
            } else {
                $item->nama_musim = $item->jenis_kamar->tarif_musim[0]->musim->name;
            }
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
     * Display a listing of the resource.
     */
    public function dashboard(Request $request)
    {
        // if login role is SM
        if (Auth::user()->role == "SM") {
            if (
                Carbon::now()->format('Y-m-d') >
                Carbon::parse($request->start_date)->subDays(7)->format('Y-m-d')
            ) {
                // return api
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa booking mendadak, harus 1 minggu setelah hari ini',
                ], 400);
            }
        }


        // validate $request->start_date and $request->end_date if null
        if ($request->start_date == null || $request->end_date == null) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Tanggal tidak boleh kosong',
            ], 422);
        }

        // validate $request->start_date must after date now and $request->end_date must after $request->start_date
        if (
            Carbon::now()->format('Y-m-d') > Carbon::parse($request->start_date)->format('Y-m-d')
            || $request->start_date >= $request->end_date
        ) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Tanggal tidak valid',
            ], 422);
        }

        // get data kamar by musim where start_date is date now
        $kamars = kamar::with(['jenis_kamar' => function ($query) use ($request) {
            $query->select('id', 'name', 'bed', 'total_bed', 'luas_kamar', 'harga_default')
                ->with(['tarif_musim' => function ($query) use ($request) {
                    $query->select('id', 'jenis_kamar_id', 'musim_id', 'harga')
                        ->with(['musim' => function ($query) {
                            $query->select('id', 'name', 'start_date', 'end_date');
                        }])
                        ->whereHas('musim', function ($query) use ($request) {
                            $query->whereDate('start_date', '<=', Carbon::parse($request->start_date)->format('Y-m-d'))
                                ->WhereDate('end_date', '>=', Carbon::parse($request->end_date)->format('Y-m-d'));
                        });
                }]);
        }])
            ->with(['transaksi_kamar' => function ($query) use ($request) {
                $query->select('id', 'reservasi_id', 'kamar_id', 'total_harga')
                    ->with(['reservasi' => function ($query) {
                        $query->select('id', 'customer_id', 'tanggal_reservasi', 'tanggal_end_reservasi')
                            ->with(['customer' => function ($query) {
                                $query->select('id', 'name', 'email');
                            }]);
                    }])
                    ->whereHas('reservasi', function ($query) use ($request) {
                        $query->whereDate('tanggal_reservasi', '<', Carbon::parse($request->end_date)->format('Y-m-d')) //end date
                            ->whereDate('tanggal_end_reservasi', '>', Carbon::parse($request->start_date)->format('Y-m-d')) //start date
                            ->whereNot('status', 'cancel');
                    });
            }])
            ->where('status', 'available')
            ->get();

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
            // nama musim
            if (count($item->jenis_kamar->tarif_musim) == 0) {
                $item->nama_musim = 'tidak ada musim';
            } else {
                $item->nama_musim = $item->jenis_kamar->tarif_musim[0]->musim->name;
            }
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

            if (count($item->transaksi_kamar) != 0) {
                unset($item);
            } else {
                // unset($item->id);
                unset($item->jenis_kamar_id);
                unset($item->created_at);
                unset($item->updated_at);
                unset($item->jenis_kamar);
                unset($item->transaksi_kamar);
                return $item;
            }
        });

        // filter data null not show/remove
        $data = $data->filter(function ($item) {
            return !is_null($item);
        })->values()->all();

        // Group data by 'nama_kamar' and 'tipe_bed'
        $groupedData = collect($data)->groupBy(function ($item) {
            return $item->nama_kamar . '-'
                . $item->tipe_bed . '-'
                . $item->total_bed . '-'
                . $item->nama_musim . '-'
                . $item->luas_kamar . '-'
                . $item->harga;
        });

        // Initialize an array to store the grouped result
        $result = [];

        // Loop through the grouped data and calculate the count
        $groupedData->each(function ($items, $key) use (&$result) {
            list($namaKamar, $tipeBed, $total_bed, $nama_musim, $luas_kamar, $harga) = explode('-', $key);

            // unset attribute at items
            $items = collect($items)->map(function ($item) {
                unset($item->nama_kamar);
                unset($item->tipe_bed);
                unset($item->total_bed);
                unset($item->nama_musim);
                unset($item->luas_kamar);
                unset($item->status);
                unset($item->harga);
                return $item;
            })->values()->all();

            $result[] = [
                'nama_kamar' => $namaKamar,
                'tipe_bed' => $tipeBed,
                'total_bed' => $total_bed,
                'nama_musim' => $nama_musim,
                'luas_kamar' => $luas_kamar,
                'harga' => $harga,
                'ketersediaan' => count($items),
                'data_kamar' => $items,
            ];
        });

        // if result = []
        if (count($result) == 0) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada kamar yang tersedia',
            ], 404);
        }

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Daftar data kamar',
            'data' => $result
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
        $kamar = kamar::with(['jenis_kamar' => function ($query) {
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
        }])->find($id);

        if (count($kamar->jenis_kamar->tarif_musim) == 0) {
            $kamar->nama_musim = 'tidak ada musim';
        } else {
            $kamar->nama_musim = $kamar->jenis_kamar->tarif_musim[0]->musim->name;
        }
        // if $kamar->jenis_kamar->tarif_musim = []
        if (count($kamar->jenis_kamar->tarif_musim) == 0) {
            // harga
            $kamar->jenis_kamar->harga_default = $kamar->jenis_kamar->harga_default;
        } else {
            // harga
            $kamar->jenis_kamar->harga_default = $kamar->jenis_kamar->tarif_musim[0]->harga;
        }

        unset($kamar->jenis_kamar->tarif_musim);

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
