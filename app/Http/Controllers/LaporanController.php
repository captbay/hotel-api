<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function laporan1(Request $request)
    {
        $year = $request->tahun; // Ambil tahun dari request

        // Daftar bulan dalam bahasa Indonesia
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $monthlyCounts = DB::table('customers')
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total_customer'))
            ->whereYear('created_at', $year) // Filter berdasarkan tahun
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Buat array kosong untuk hasil akhir
        $result = [];

        // Loop melalui hasil query
        foreach ($monthlyCounts as $count) {
            $result[] = [
                'bulan' => $bulanIndonesia[$count->bulan],
                'total_customer' => $count->total_customer
            ];
        }

        // Tambahkan bulan-bulan yang tidak ada dalam hasil query dengan total customer 0
        foreach ($bulanIndonesia as $bulan) {
            $found = false;

            foreach ($result as $count) {
                if ($count['bulan'] === $bulan) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $result[] = ['bulan' => $bulan, 'total_customer' => 0];
            }
        }

        // Urutkan hasil berdasarkan indeks bulan
        usort($result, function ($a, $b) use ($bulanIndonesia) {
            return array_search($a['bulan'], $bulanIndonesia) - array_search($b['bulan'], $bulanIndonesia);
        });
        $totalCustomer = 0;

        foreach ($result as $item) {
            $totalCustomer += $item['total_customer'];
        }
        $data = [
            'tahun' => $year,
            'data' => $result,
            'cetak' => Carbon::now()->format('d F Y'),
            'total' => $totalCustomer
        ];
        $pdf = Pdf::loadview('laporan1', $data);

        // return $pdf->download('invoice.pdf');
        // return $pdf->output();
        return $pdf->stream();
    }

    public function laporan2(Request $request)
    {
        $year = $request->tahun; // Ambil tahun dari request
        $reservasi = reservasi::with(
                    'customer',
                    'pegawai',
                    'transaksi_kamar.kamar.jenis_kamar',
                    'transaksi_fasilitas_tambahan.fasilitas_tambahan',
                )
                ->with(['invoices' => function ($q) use (&$year){
                    $q->whereYear('tanggal_lunas_nota', $year);
                }])
                ->select('*', DB::raw("
                CASE
                    WHEN pegawai_id IS NOT NULL THEN 'grup'
                    ELSE 'personal'
                END as type
                "))->get();
        $invoice = [];
        $reservasi->each(function ($item) use(&$invoice) {
            // Memeriksa apakah ada invoice dan menambahkan 'type' ke dalamnya
            if ($item->invoices) {
                $item->invoices->type = $item->type;
                $invoice[] = $item->invoices;
            }
        });
        $data = collect($invoice);
        $groupedData = $data->groupBy(function ($item) {
            return Carbon::parse($item['tanggal_lunas_nota'])->format('F'); // F adalah format bulan
        });
        $sortedData = $groupedData->reverse();
        $bulanReferensi = [
            "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
        ];
        $result = [];

        foreach ($sortedData as $month => $data) {
            $totalTypeGrup = 0;
            $totalTypePersonal = 0;

            foreach ($data as $entry) {
                if ($entry["type"] === "grup") {
                    $totalTypeGrup += $entry["total_harga"];
                } elseif ($entry["type"] === "personal") {
                    $totalTypePersonal += $entry["total_harga"];
                }
            }

            // Menambahkan hasil perhitungan ke hasil akhir
            $result[$month] = [
                "total_type_grup" => $totalTypeGrup,
                "total_type_personal" => $totalTypePersonal,
            ];
        }
        $hasilManipulasi = [];
            // return $result;
            foreach ($bulanReferensi as $bulan) {
                $grup = 0;
                $personal = 0;

                // Periksa apakah ada data untuk bulan ini di $result
                if (isset($result[$bulan])) {
                    $grup = $result[$bulan]['total_type_grup'];
                    $personal = $result[$bulan]['total_type_personal'];
                }

                // Tambahkan data ke hasil manipulasi
                $hasilManipulasi[] = [
                    "bulan" => $bulan,
                    "grup" => $grup,
                    "personal" => $personal,
                    "total" => $grup + $personal
                ];
            }
        $totalPendapatan = 0;

        foreach ($hasilManipulasi as $item) {
            // Menambahkan total dari setiap bulan ke total pendapatan
            $totalPendapatan += $item["total"];
        }
        $data = [
            'tahun' => $year,
            'data' => $hasilManipulasi,
            'cetak' => Carbon::now()->format('d F Y'),
            'total' => $totalPendapatan
        ];
        $pdf = Pdf::loadview('laporan2', $data);

        // return $pdf->download('invoice.pdf');
        // return $pdf->output();
        return $pdf->stream();
    }

    public function laporan2Data(Request $request)
    {
        $year = $request->tahun; // Ambil tahun dari request
        $reservasi = reservasi::with(
                    'customer',
                    'pegawai',
                    'transaksi_kamar.kamar.jenis_kamar',
                    'transaksi_fasilitas_tambahan.fasilitas_tambahan',
                )
                ->with(['invoices' => function ($q) use (&$year){
                    $q->whereYear('tanggal_lunas_nota', $year);
                }])
                ->select('*', DB::raw("
                CASE
                    WHEN pegawai_id IS NOT NULL THEN 'grup'
                    ELSE 'personal'
                END as type
                "))->get();
        $invoice = [];
        $reservasi->each(function ($item) use(&$invoice) {
            // Memeriksa apakah ada invoice dan menambahkan 'type' ke dalamnya
            if ($item->invoices) {
                $item->invoices->type = $item->type;
                $invoice[] = $item->invoices;
            }
        });
        $data = collect($invoice);
        $groupedData = $data->groupBy(function ($item) {
            return Carbon::parse($item['tanggal_lunas_nota'])->format('F'); // F adalah format bulan
        });
        $sortedData = $groupedData->reverse();
        $bulanReferensi = [
            "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
        ];
        $result = [];

        foreach ($sortedData as $month => $data) {
            $totalTypeGrup = 0;
            $totalTypePersonal = 0;

            foreach ($data as $entry) {
                if ($entry["type"] === "grup") {
                    $totalTypeGrup += $entry["total_harga"];
                } elseif ($entry["type"] === "personal") {
                    $totalTypePersonal += $entry["total_harga"];
                }
            }

            // Menambahkan hasil perhitungan ke hasil akhir
            $result[$month] = [
                "total_type_grup" => $totalTypeGrup,
                "total_type_personal" => $totalTypePersonal,
            ];
        }
        $hasilManipulasi = [];
            // return $result;
            foreach ($bulanReferensi as $bulan) {
                $grup = 0;
                $personal = 0;

                // Periksa apakah ada data untuk bulan ini di $result
                if (isset($result[$bulan])) {
                    $grup = $result[$bulan]['total_type_grup'];
                    $personal = $result[$bulan]['total_type_personal'];
                }

                // Tambahkan data ke hasil manipulasi
                $hasilManipulasi[] = [
                    "bulan" => $bulan,
                    "grup" => $grup,
                    "personal" => $personal,
                    "total" => $grup + $personal
                ];
            }
        return response()->json([
            'success' => true,
            'message' => 'Data Laporan 2',
            'data' => $hasilManipulasi
        ], 200);
    }

    public function laporan3(Request $request)
    {
        $year = $request->tahun;
        $month = $request->bulan;
        $reservasi = reservasi::with(
                    'customer',
                    'pegawai',
                    'transaksi_kamar.kamar.jenis_kamar',
                )
                ->select('*', DB::raw("
                CASE
                    WHEN pegawai_id IS NOT NULL THEN 'grup'
                    ELSE 'personal'
                END as type
                "))
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();
        // Menyimpan nilai 'type' ke dalam setiap elemen 'transaksi_kamar' menggunakan each
        $reservasi->each(function ($item) {
            $type = $item->type;

            // Menambahkan 'type' ke dalam setiap elemen 'transaksi_kamar'
            if ($item->transaksi_kamar) {
                $item->transaksi_kamar->each(function ($transaksi) use ($type) {
                    $transaksi->type = $type;
                    $transaksi->jenis = $transaksi->kamar->jenis_kamar->name;
                    unset($transaksi->kamar);
                });
            }
        });
        $kamar = $reservasi->pluck('transaksi_kamar')->toArray();
        $dataBaru = array_merge(...$kamar);
        $jenis_kamar = [
            'SUPERIOR',
            'DOUBLE DELUXE',
            'EXECUTIVE DELUXE',
            'JUNIOR SUITE'
        ];
        $hasil = [];

        // Membuat template untuk setiap jenis kamar dengan nilai awal 0
        foreach ($jenis_kamar as $jenis) {
            $hasil[$jenis] = ['jenis' => $jenis, 'personal' => 0, 'grup' => 0, 'total' => 0];
        }

        // Iterasi melalui data reservasi dan menghitung jumlahnya
        foreach ($dataBaru as $reservasi) {
            if (array_key_exists($reservasi['jenis'], $hasil)) {
                if ($reservasi['type'] === 'personal') {
                    $hasil[$reservasi['jenis']]['personal'] += 1;
                } elseif ($reservasi['type'] === 'grup') {
                    $hasil[$reservasi['jenis']]['grup'] += 1;
                }
                // Menambahkan jumlah ke total
                $hasil[$reservasi['jenis']]['total'] = $hasil[$reservasi['jenis']]['personal'] + $hasil[$reservasi['jenis']]['grup'];
            }
        }
        // Mengonversi hasil menjadi array indeks
        $hasilAkhir = array_values($hasil);
        $totalPendapatan = 0;

        foreach ($hasilAkhir as $item) {
            // Menambahkan total dari setiap bulan ke total pendapatan
            $totalPendapatan += $item["total"];
        }
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $data = [
            'tahun' => $year,
            'bulan' => $namaBulan[$month],
            'data' => $hasilAkhir,
            'cetak' => Carbon::now()->format('d F Y'),
            'total' => $totalPendapatan
        ];
        $pdf = Pdf::loadview('laporan3', $data);

        // return $pdf->download('invoice.pdf');
        // return $pdf->output();
        return $pdf->stream();
    }

    public function laporan3Data(Request $request)
    {
        $year = $request->tahun;
        $month = $request->bulan;
        $reservasi = reservasi::with(
                    'customer',
                    'pegawai',
                    'transaksi_kamar.kamar.jenis_kamar',
                )
                ->select('*', DB::raw("
                CASE
                    WHEN pegawai_id IS NOT NULL THEN 'grup'
                    ELSE 'personal'
                END as type
                "))
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();
        // Menyimpan nilai 'type' ke dalam setiap elemen 'transaksi_kamar' menggunakan each
        $reservasi->each(function ($item) {
            $type = $item->type;

            // Menambahkan 'type' ke dalam setiap elemen 'transaksi_kamar'
            if ($item->transaksi_kamar) {
                $item->transaksi_kamar->each(function ($transaksi) use ($type) {
                    $transaksi->type = $type;
                    $transaksi->jenis = $transaksi->kamar->jenis_kamar->name;
                    unset($transaksi->kamar);
                });
            }
        });
        $kamar = $reservasi->pluck('transaksi_kamar')->toArray();
        $dataBaru = array_merge(...$kamar);
        $jenis_kamar = [
            'SUPERIOR',
            'DOUBLE DELUXE',
            'EXECUTIVE DELUXE',
            'JUNIOR SUITE'
        ];
        $hasil = [];

        // Membuat template untuk setiap jenis kamar dengan nilai awal 0
        foreach ($jenis_kamar as $jenis) {
            $hasil[$jenis] = ['jenis' => $jenis, 'personal' => 0, 'grup' => 0, 'total' => 0];
        }

        // Iterasi melalui data reservasi dan menghitung jumlahnya
        foreach ($dataBaru as $reservasi) {
            if (array_key_exists($reservasi['jenis'], $hasil)) {
                if ($reservasi['type'] === 'personal') {
                    $hasil[$reservasi['jenis']]['personal'] += 1;
                } elseif ($reservasi['type'] === 'grup') {
                    $hasil[$reservasi['jenis']]['grup'] += 1;
                }
                // Menambahkan jumlah ke total
                $hasil[$reservasi['jenis']]['total'] = $hasil[$reservasi['jenis']]['personal'] + $hasil[$reservasi['jenis']]['grup'];
            }
        }
        // Mengonversi hasil menjadi array indeks
        $hasilAkhir = array_values($hasil);
        $totalPendapatan = 0;

        foreach ($hasilAkhir as $item) {
            // Menambahkan total dari setiap bulan ke total pendapatan
            $totalPendapatan += $item["total"];
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Laporan 3',
            'data' => $hasilAkhir
        ], 200);
    }
}
