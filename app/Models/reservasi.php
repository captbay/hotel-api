<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservasi extends Model
{
    use HasFactory;

    // table
    protected $table = 'reservasis';

    // fillable
    protected $fillable = [
        'customer_id',
        'pegawai_id',
        'kode_booking',
        'tanggal_reservasi',
        'tanggal_end_reservasi',
        'check_in',
        'check_out',
        'status',
        'dewasa',
        'anak',
        'total_jaminan',
        'total_deposit',
        'total_harga',
        'tanggal_pembayaran_lunas',
        'note'
    ];

    // relation to customer
    public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id');
    }

    // relation to pegawai
    public function pegawai()
    {
        return $this->belongsTo(pegawai::class, 'pegawai_id');
    }

    // relation has many to transaksi_kamar
    public function transaksi_kamar()
    {
        return $this->hasMany(transaksi_kamar::class, 'reservasi_id', 'id');
    }

    //  relation has many to transaksi_fasilitas_tambahan
    public function transaksi_fasilitas_tambahan()
    {
        return $this->hasMany(transaksi_fasilitas_tambahan::class, 'reservasi_id', 'id');
    }

    // has one invoices
    public function invoices()
    {
        return $this->hasOne(invoice::class, 'reservasi_id', 'id');
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
