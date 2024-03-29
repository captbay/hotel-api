<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kamar extends Model
{
    use HasFactory;

    protected $table = 'kamars';

    protected $fillable = [
        'jenis_kamar_id',
        'no_kamar',
        'status', // available, unavailable
    ];

    // belongs to
    public function jenis_kamar()
    {
        return $this->belongsTo(jenis_kamar::class, 'jenis_kamar_id');
    }

    // has many to transaksi_kamar
    public function transaksi_kamar()
    {
        return $this->hasMany(transaksi_kamar::class, 'kamar_id', 'id');
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
