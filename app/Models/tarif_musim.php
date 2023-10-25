<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tarif_musim extends Model
{
    use HasFactory;

    protected $table = 'tarif_musims';

    protected $fillable = [
        'jenis_kamar_id',
        'musim_id',
        'harga',
    ];

    // belongs to
    public function jenis_kamar()
    {
        return $this->belongsTo(jenis_kamar::class, 'jenis_kamar_id');
    }

    public function musim()
    {
        return $this->belongsTo(musim::class, 'musim_id');
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
