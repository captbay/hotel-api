<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fasilitas_tambahan extends Model
{
    use HasFactory;

    protected $table = 'fasilitas_tambahans';

    protected $fillable = [
        'name',
        'harga',
    ];

    // relation to transaksi_fasilitas_tambahan
    public function transaksi_fasilitas_tambahan()
    {
        return $this->hasMany(transaksi_fasilitas_tambahan::class, 'fasilitas_tambahan_id', 'id');
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
