<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatePremi extends Model
{
    use HasFactory;

    protected $table = 'mst_rate_premi';

    protected $fillable = ['masa_asuransi1', 'masa_asuransi2', 'jenis', 'rate'];
}
