<?php

namespace App\Http\Controllers;

use App\Models\reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;


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
            'transaksi_kamar.kamar.jenis_kamar',
            'transaksi_fasilitas_tambahan.fasilitas_tambahan'
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
            'transaksi_kamar.kamar.jenis_kamar',
            'transaksi_fasilitas_tambahan.fasilitas_tambahan'
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
        // TODO:fasilitan tambahan berbayar belom disini, dia dibuat sama FO
        // TODO:sekarang itu cuma note aja

        // validate request
        $validatedData = Validator::make($request->all(), [
            'customer_id' => 'required|integer',
            // 'pegawai_id' => 'integer', // kalo ada berarti reservasi grup
            'tanggal_reservasi' => 'required|date',
            'tanggal_end_reservasi' => 'required|date',
            'dewasa' => 'required|integer',
            'anak' => 'required|integer',
            // 'note' => 'string',
            'kamar' => 'required|array',
            'kamar.*.kamar_id' => 'required|integer',
            'kamar.*.total_harga' => 'required|integer',
        ]);

        // if validate failed
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->all()], 422);
        }

        //jarak hari berupa int antara tanggal_reservasi dan tanggal_end_reservasi
        $tanggal_reservasi = Carbon::parse($request->tanggal_reservasi);
        $tanggal_end_reservasi = Carbon::parse($request->tanggal_end_reservasi);

        $jarak_hari = $tanggal_reservasi->diffInDays($tanggal_end_reservasi);

        // count total harga in kamar and fasilitas tambahan
        $total_harga_kamar = 0;
        foreach ($request->kamar as $kamar) {
            $total_harga_kamar += $kamar['total_harga'] * $jarak_hari;
        }

        // count total harga
        $total_harga = $total_harga_kamar; //+ $total_harga_fasilitas_tambahan;

        // generate id transaksi
        if (DB::table('reservasis')->count() == 0) {
            $id_terakhir = 0;
        } else {
            $id_terakhir = reservasi::latest('id')->first()->id;
        }
        $count = $id_terakhir + 1;
        $id_generate = sprintf("%03d", $count);

        //membuat angka dengan format dmy
        $digitDate = Carbon::parse(now())->format('dmy');

        // todo: belum menghitung untuk harga, coba buat dulu

        if ($request->pegawai_id == null) {
            $no_booking = 'P' . $digitDate . '-' . $id_generate;

            // jaminan = total pembayaran

            // create reservasi for personal
            $reservasi = reservasi::create([
                'customer_id' => $request->customer_id,
                'pegawai_id' => $request->pegawai_id,
                'kode_booking' => $no_booking,
                'tanggal_reservasi' => $request->tanggal_reservasi,
                'tanggal_end_reservasi' => $request->tanggal_end_reservasi,
                'status' => 'belum cekin', //belum cek in
                'dewasa' => $request->dewasa,
                'anak' => $request->anak,
                'total_jaminan' => $total_harga,  // ini harga yang bakal dibayar dulu
                // 'total_deposit' => null, //setelah cek in diminta 300k
                'total_harga' => $total_harga,
                'tanggal_pembayaran_lunas' => Carbon::now()->format('Y-m-d'), //setelah cek out baru lunas kalo grup
                'note' => $request->note,
            ]);
        } else {
            $no_booking = 'G' . $digitDate . '-' . $id_generate;

            // jaminan 50% dari total pembayaran
            $total_harga_jaminan = $total_harga * 50 / 100;

            // create reservasi for grup
            $reservasi = reservasi::create([
                'customer_id' => $request->customer_id,
                'pegawai_id' => $request->pegawai_id,
                'kode_booking' => $no_booking,
                'tanggal_reservasi' => $request->tanggal_reservasi,
                'tanggal_end_reservasi' => $request->tanggal_end_reservasi,
                'status' => 'belom bayar jaminan', //belum cek in
                'dewasa' => $request->dewasa,
                'anak' => $request->anak,
                'total_jaminan' => $total_harga_jaminan, // ini harga yang bakal dibayar dulu
                // 'total_deposit' => null, //setelah cek in diminta 300k
                'total_harga' => $total_harga,
                //'tanggal_pembayaran_lunas' => null, //setelah cek out baru lunas kalo grup
                'note' => $request->note,
            ]);
        }

        // if create reservasi failed
        if (!$reservasi) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Reservasi gagal dibuat',
            ], 500);
        }

        // create transaksi kamar
        foreach ($request->kamar as $kamar) {
            $reservasi->transaksi_kamar()->create([
                'kamar_id' => $kamar['kamar_id'],
                'total_harga' => $kamar['total_harga'] * $jarak_hari,
            ]);
        }

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Reservasi berhasil dibuat',
            'id_reservasi' => $reservasi->id
        ], 201);
    }

    // create tanda terima reservasi pdf
    public function createTandaTerima($id)
    {
        //find
        $reservasi = reservasi::with(
            'customer',
            'pegawai',
            'transaksi_kamar.kamar.jenis_kamar',
            'transaksi_fasilitas_tambahan.fasilitas_tambahan'
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

        $tanggal_sekarang = Carbon::now()->format('d/M/Y');
        $reservasi->tanggal_reservasi = Carbon::parse($reservasi->tanggal_reservasi)->format('d/M/Y');
        $reservasi->tanggal_end_reservasi = Carbon::parse($reservasi->tanggal_end_reservasi)->format('d/M/Y');
        $reservasi->tanggal_pembayaran_lunas = Carbon::parse($reservasi->tanggal_pembayaran_lunas)->format('d/M/Y');


        // TODO: kurang jumlah kamar per nama kamar yang sama
        // make new collection to save where jenis_kamar->name is same in $reservasi->transaksi_kamar->kamar->jenis_kamar->name

        $data = [
            'reservasi' => $reservasi,
            'tanggal_sekarang' => $tanggal_sekarang
        ];

        $pdf = Pdf::loadview('tanda-terima-reservasi', $data);

        // return $pdf->output(); //ketika data sudah fix fi download 
        return $pdf->stream();
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
            'transaksi_kamar.kamar.jenis_kamar',
            'transaksi_fasilitas_tambahan.fasilitas_tambahan'
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

    // show reservasi yang bisa dibatalkan
    public function cancelList()
    {
        // find data reservasi where can cancel
        //get
        $reservasis = reservasi::with(
            'customer',
            'pegawai',
            'transaksi_kamar.kamar.jenis_kamar',
            'transaksi_fasilitas_tambahan.fasilitas_tambahan'
        )
            ->where('status', 'belum cekin')
            ->orWhere('status', 'cancel')
            ->get();

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Daftar data reservasi yang bisa di cancel',
            'data' => $reservasis
        ], 200);
    }

    // put pembatalan reservasi menggunakan id reservasi
    public function cancel($id)
    {
        // find data reservasi
        $reservasi = reservasi::find($id);

        // if data reservasi null
        if (!$reservasi) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Data reservasi tidak ditemukan',
            ], 404);
        }

        // if date now > tanggal_reservasi
        if (
            Carbon::now()->format('Y-m-d') >
            Carbon::parse($reservasi->tanggal_reservasi)->format('Y-m-d')
        ) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak bisa dibatalkan, karena tanggal hari ini melebihi tanggal reservasi',
            ], 400);
        }

        // update data reservasi
        $reservasi->update([
            'status' => 'cancel'
        ]);

        // uang jaminan = 0 ketika maksimal seminggu sebelum tanggal_reservasi
        if (
            Carbon::now()->format('Y-m-d') >
            Carbon::parse($reservasi->tanggal_reservasi)->subDays(7)->format('Y-m-d')
        ) {
            $reservasi->update([
                'total_jaminan' => 0
            ]);

            // return api
            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibatalkan dan uang anda dibalikan',
            ], 200);
        } else {
            // return api
            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibatalkan',
            ], 200);
        }
    }

    // show reservasi yang bisa dibatalkan
    public function listReservasiGrup()
    {
        // find data reservasi where can cancel
        //get
        $reservasis = reservasi::with(
            'customer',
            'pegawai',
            'transaksi_kamar.kamar.jenis_kamar',
            'transaksi_fasilitas_tambahan.fasilitas_tambahan'
        )
            ->whereNot('pegawai_id', null)
            ->where('status', 'belom bayar jaminan')
            ->get();

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Daftar data reservasi grup',
            'data' => $reservasis
        ], 200);
    }

    // input uang jaminan grup
    public function inputJaminanGrup($id)
    {
        // find data reservasi id
        $reservasi = reservasi::find($id);

        // if data reservasi null
        if (!$reservasi) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Data reservasi tidak ditemukan',
            ], 404);
        }

        // batas bayar  maksimal seminggu sebelum tanggal_checkin
        if (
            Carbon::now()->format('Y-m-d') >
            Carbon::parse($reservasi->tanggal_reservasi)->subDays(7)->format('Y-m-d')
        ) {
            // return api
            return response()->json([
                'success' => false,
                'message' => 'Batas pembayaran jaminan sudah lewat',
            ], 400);
        }

        // update data reservasi
        $reservasi->update([
            'status' => 'belum cekin',
            'tanggal_pembayaran_lunas' => Carbon::now()->format('Y-m-d'),
        ]);

        // return api
        return response()->json([
            'success' => true,
            'message' => 'Jaminan berhasil dibayar',
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