<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstFormAsuransi extends Model
{
    use HasFactory;

    protected $table = 'mst_form_asuransi';

    public function perusahaanAsuransi()
    {
        return $this->belongsTo(MstPerusahaanAsuransi::class, 'perusahaan_id', 'id');
    }
    public function itemAsuransi()
    {
        return $this->belongsTo(MstFormItemAsuransi::class, 'form_item_asuransi_id', 'id');
    }
}
