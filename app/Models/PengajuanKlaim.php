<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKlaim extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_klaim';

    public function pembayaranPremi()
    {
        return $this->belongsTo(PembayaranPremi::class, 'pembayaran_premi_id', 'id');
    }
}
