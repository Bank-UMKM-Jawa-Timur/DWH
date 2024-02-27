<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPremiDetail extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_premi_detail';

    public function pembayaranPremi()
    {
        return $this->belongsTo(PembayaranPremi::class, 'pembayaran_premi_id', 'id');
    }

    public function asuransi()
    {
        return $this->belongsTo(Asuransi::class, 'asuransi_id', 'id');
    }
}
