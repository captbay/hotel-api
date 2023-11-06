<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    use HasFactory;

    // table
    protected $table = 'invoices';

    // fillable
    protected $fillable = [
        'reservasi_id',
        'pegawai_id',
        'no_invoice',
        'tanggal_lunas_nota',
        'total_harga',
        'total_pajak',
        'total_pembayaran'
    ];

    // relation to reservasi
    public function reservasi()
    {
        return $this->belongsTo(reservasi::class, 'reservasi_id');
    }

    // relation to pegawai
    public function pegawai()
    {
        return $this->belongsTo(pegawai::class, 'pegawai_id');
    }

    public function getCreatedAtAttribute($value)
    {
        if (!is_null($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute($value)
    {
        if (!is_null($this->attributes['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
