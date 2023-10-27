<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerusahaanAsuransi extends Model
{
    use HasFactory;

    protected $table = 'mst_perusahaan_asuransi';

    protected $fillable = ['nama', 'alamat', 'telp'];
}
