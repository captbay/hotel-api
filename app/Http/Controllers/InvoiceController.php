<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use App\Http\Requests\StoreinvoiceRequest;
use App\Http\Requests\UpdateinvoiceRequest;
use App\Models\reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
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
            'message' => 'Invoice Berhasil Dibuat',
        ], 200);
    }

    public function invoice($id){
        $reservasi = reservasi::with('pegawai.user')
                            ->with('customer.user')
                            ->with('transaksi_fasilitas_tambahan.fasilitas_tambahan')
                            ->with('transaksi_kamar.kamar.jenis_kamar.tarif_musim')
                            ->find($id);

        $kamar = [];
        $total_harga_kamar = 0;
        $reservasi->transaksi_kamar->each(function ($item) use (&$kamar, &$total_harga_kamar) {
            $kamar[] = [
                'jenis_kamar' => $item->kamar->jenis_kamar->name,
                'bed' => $item->kamar->jenis_kamar->bed,
                'jumlah' => 1,
                'harga' => $item->kamar->jenis_kamar->harga_default,
                'sub_total' => $item->total_harga
            ];
            $total_harga_kamar += $item->total_harga;
        });

        $layanan = [];
        $total_harga_layanan = 0;
        $reservasi->transaksi_fasilitas_tambahan->each(function ($item) use (&$layanan, &$total_harga_layanan) {
            $layanan[] = [
                'layanan' => $item->fasilitas_tambahan->name,
                'tanggal' => Carbon::parse($item->created_at)->format('d/M/Y'),
                'jumlah' => $item->jumlah,
                'harga' => $item->fasilitas_tambahan->harga,
                'sub_total' => $item->total_harga
            ];
            $total_harga_layanan += $item->total_harga;
        });

        $invoice = invoice::with('pegawai.user')->where('reservasi_id', $id)->first();

        $data = [
            'data' => [
                'tanggal' => Carbon::parse($invoice->created_at)->format('d/M/Y'),
                'nomor_invoice' => $invoice->no_invoice,
                'front_office' => $invoice->pegawai ? $invoice->pegawai->name : null ,
                'id_booking' => $reservasi->kode_booking,
                'nama_pelanggan' => $reservasi->customer->name,
                'alamat' => $reservasi->customer->address,
                'check_in' => Carbon::parse($reservasi->check_in)->format('d/M/Y'),
                'check_out' => Carbon::parse($reservasi->check_out)->format('d/M/Y'),
                'dewasa' => $reservasi->dewasa,
                'anak_anak' => $reservasi->anak,
                'kamar' => $kamar,
                'total_harga_kamar' =>$total_harga_kamar,
                'layanan' => $layanan,
                'total_harga_fasilitas' => $total_harga_layanan,
                'pajak' => $invoice->total_pajak,
                'total' =>  $total_harga_kamar + $total_harga_layanan + $invoice->total_pajak,
                'jaminan' => $reservasi->total_jaminan,
                'deposit' => $reservasi->total_deposit,
                'tunai' => $total_harga_kamar + $total_harga_layanan + $invoice->total_pajak - $reservasi->total_jaminan - $reservasi->total_deposit,
            ]
        ];
        $pdf = Pdf::loadview('invoices', $data);

        // return $pdf->download('invoice.pdf');
        // return $pdf->output();
        return $pdf->stream();
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
