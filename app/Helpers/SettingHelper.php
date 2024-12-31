<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingHelper
{
    /**
     * Mendapatkan nilai PPN berdasarkan tanggal transaksi.
     *
     * @param string $date
     * @return float
     */
    public static function getPpn($date = null)
    {
        $date = $date ?? now()->toDateString();
        return Setting::getPpnByDate($date);
    }
}
