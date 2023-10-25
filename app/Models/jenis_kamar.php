<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenis_kamar extends Model
{
    use HasFactory;

    protected $table = 'jenis_kamars';

    protected $fillable = [
        'name',
        'bed',
        'total_bed',
        'luas_kamar',
        'harga_default',
    ];

    public function kamar()
    {
        return $this->hasMany(kamar::class, 'jenis_kamar_id', 'id');
    }

    public function tarif_musim()
    {
        return $this->hasMany(tarif_musim::class, 'jenis_kamar_id', 'id');
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
