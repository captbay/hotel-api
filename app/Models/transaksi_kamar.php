<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi_kamar extends Model
{
    use HasFactory;

    // table
    protected $table = 'transaksi_kamars';

    // fillable
    protected $fillable = [
        'reservasi_id',
        'kamar_id',
        'total_harga',
    ];

    // relation to reservasi
    public function reservasi()
    {
        return $this->belongsTo(reservasi::class, 'reservasi_id');
    }

    // relation to kamar
    public function kamar()
    {
        return $this->belongsTo(kamar::class, 'kamar_id');
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
