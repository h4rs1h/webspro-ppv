<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['setting_key', 'setting_value', 'effective_date'];
    public $timestamps = true;

    /**
     * Mendapatkan nilai PPN berdasarkan tanggal transaksi.
     *
     * @param string $date
     * @return float
     */
    public static function getPpnByDate($date)
    {
        return self::where('setting_key', 'ppn')
            ->where('effective_date', '<=', $date)
            ->orderBy('effective_date', 'desc')
            ->first()
            ->setting_value ?? 0;
    }
}
