<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKlaim extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_klaim';

    public function asuransi()
    {
        return $this->belongsTo(Asuransi::class, 'asuransi_id', 'id');
    }
}
