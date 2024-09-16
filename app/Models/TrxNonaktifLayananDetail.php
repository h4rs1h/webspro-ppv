<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxNonaktifLayananDetail extends Model
{
    use HasFactory;
    protected $table = 'trx_nonaktif_layanan_detail';

    protected $fillable = ['id','tipe_trx','id_trx','nomer_trx','pelanggan_id','jml_hari_telat','tgl_jatuh_tempo','periode_pemakaian','gtot_tagihan','tgl_req_off'];



}
