<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use App\Http\Requests\StoreinvoiceRequest;
use App\Http\Requests\UpdateinvoiceRequest;
use App\Models\reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id)
    {
        $reservasi = reservasi::with('pegawai.user')
                                ->with('transaksi_fasilitas_tambahan.fasilitas_tambahan')
                                ->with('transaksi_kamar.kamar.jenis_kamar.tarif_musim')
                                ->find($id);

        $totalHargaFasilitasTambahan = 0;

        $reservasi->transaksi_fasilitas_tambahan->each(function ($transaksi) use (&$totalHargaFasilitasTambahan) {
            $totalHargaFasilitasTambahan += $transaksi->total_harga;
        });
        $reservasi->total_harga_fasilitas_tambahan = $totalHargaFasilitasTambahan;

        if($reservasi->status != 'selesai'){
            return response()->json([
                'success' => false,
                'message' => 'Invoices Tidak Bisa Dibuat',
            ], 403);
        }

        $invoice = invoice::where('reservasi_id', $id)->first();
        if($invoice){
            return response()->json([
                'success' => false,
                'message' => "Invoices Sudah Tersedia Untuk Reservasi ini dengan No Invoice : ".$invoice->no_invoice."" ,
            ], 403);
        }
        if (DB::table('invoices')->count() == 0) {
            $id_terakhir = 0;
        } else {
            $id_terakhir = invoice::latest('id')->first()->id;
        }
        $date = Carbon::now()->format('dmy');
        $count = $id_terakhir + 1;
        $id_generate  = sprintf("P".$date."-%03d", $count);
        $total_harga = $reservasi->total_harga + $reservasi->total_harga_fasilitas_tambahan;
        $pajak = $total_harga * 0.1;
        $total_pembayaran = $total_harga + $pajak;
        invoice::create([
            'reservasi_id' => $id,
            'no_invoice' => $id_generate,
            'pegawai_id' => $request->pegawai_id,
            'tanggal_lunas_nota' => Carbon::now()->format('Y-m-d'),
            'total_harga' => $total_harga,
            'total_pajak' => $pajak,
            'total_pembayaran' => $total_pembayaran
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Invoice Berhasi Dibuat',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
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
