<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function laporan1()
    {
        $year = 2023; // Ambil tahun dari request

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

        $data = [
            'tahun' => $year,
            'data' => $result,
            'cetak' => Carbon::now()
        ];
        $pdf = Pdf::loadview('laporan1', $data);

        // return $pdf->download('invoice.pdf');
        // return $pdf->output();
        return $pdf->stream();
    }
}
