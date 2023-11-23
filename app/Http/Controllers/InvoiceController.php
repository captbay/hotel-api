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
        $reservasi = reservasi::find($id);
        if($reservasi->status != 'selesai'){
            return response()->json([
                'success' => false,
                'message' => 'Invoices Tidak Bisa Dibuat',
            ], 403);
        }
        if (DB::table('invoices')->count() == 0) {
            $id_terakhir = 0;
        } else {
            $id_terakhir = invoice::latest('id')->first()->id;
        }
        $date = Carbon::now()->format('dmy');
        $count = $id_terakhir + 1;
        $id_generate = sprintf("P".$date."-%03d", $count);
        return $id_generate;
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
