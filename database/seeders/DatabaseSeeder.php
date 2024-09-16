<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tower;
use App\Models\Lantai;
use App\Models\Layanan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'name' => 'Harsih Rianto',
            'email' => 'harsih.hhr@bsi.ac.id',
            'password' => bcrypt('password'),
            'role' => '0',
            'no_unit' => 'AC/L3/02',
            'is_pemilik'=> '0',
            'no_identitas' => '123456789',
            'alamat' => 'Jl. Bojong koneng no.10',
            'no_telpon' => '0215875987',
            'no_hp' => '0215875987',
            'email_verified_at' => date(now()),
        ]);

            //    Lantai::factory(30)->create();
            Lantai::create(['name' => 'L1']);
            Lantai::create(['name' => 'L10']);
            Lantai::create(['name' => 'L11']);
            Lantai::create(['name' => 'L12']);
            Lantai::create(['name' => 'L15']);
            Lantai::create(['name' => 'L16']);
            Lantai::create(['name' => 'L17']);
            Lantai::create(['name' => 'L18']);
            Lantai::create(['name' => 'L19']);
            Lantai::create(['name' => 'L2']);
            Lantai::create(['name' => 'L20']);
            Lantai::create(['name' => 'L21']);
            Lantai::create(['name' => 'L23']);
            Lantai::create(['name' => 'L25']);
            Lantai::create(['name' => 'L26']);
            Lantai::create(['name' => 'L27']);
            Lantai::create(['name' => 'L28']);
            Lantai::create(['name' => 'L29']);
            Lantai::create(['name' => 'L3']);
            Lantai::create(['name' => 'L5']);
            Lantai::create(['name' => 'L6']);
            Lantai::create(['name' => 'L7']);
            Lantai::create(['name' => 'L8']);
            Lantai::create(['name' => 'L9']);

            Tower::create(['name' => 'A']);
            Tower::create(['name' => 'B']);
            Tower::create(['name' => 'C']);
            Tower::create(['name' => 'D']);
            Tower::create(['name' => 'E']);

            Layanan::create(
                [
                    'title' => 'Internet 10 Mbps',
                    'slug' => 'internet-10-mbps',
                    'spead' => '10 Mbps',
                    'benefit' => "<ul><li>Kuota Unlimited</li><li>Wifi Modem</li><li>Up to 3-4 Gadged</li><li>Include TV - Lokal Channel dan FTA</li></ul>",
                    'harga' => '350000',
                    'jenis_layanan' => 'Internet'
                ]);
            Layanan::create(
                    [
                        'title' => 'Internet 20 Mbps',
                        'slug' => 'internet-20-mbps',
                        'spead' => '20 Mbps',
                        'benefit' => "<ul><li>Kuota Unlimited</li><li>Wifi Modem</li><li>Up to 5 Gadged</li><li>Include TV - Lokal Channel dan FTA</li></ul>",
                        'harga' => '450000',
                        'jenis_layanan' => 'Internet'
                    ]);
            Layanan::create(
                        [
                            'Title' => 'Internet 40 Mbps',
                            'slug' => 'internet-40-mbps',
                            'spead' => '40 Mbps',
                            'benefit' => "<ul><li>Kuota Unlimited</li><li>Wifi Modem</li><li>Up to 10 Gadged</li><li>Include TV - Lokal Channel dan FTA</li></ul>",
                            'harga' => '550000',
                            'jenis_layanan' => 'Internet'
                        ]);
            Layanan::create(
                        [
                        'Title' => 'Internet 60 Mbps',
                        'slug' => 'internet-60-mbps',
                        'spead' => '60 Mbps',
                        'benefit' => "<ul><li>Kuota Unlimited</li><li>Wifi Modem</li><li>Up to 10 Gadged</li><li>Include TV - Lokal Channel dan FTA</li></ul>",
                        'harga' => '750000',
                        'jenis_layanan' => 'Internet'
                    ]);
            Layanan::create(
                [
                    'Title' => 'TV Extream Basic 73 Channel',
                    'slug' => 'tv-xtream-basic-73-channel',
                    'benefit' => "<ul><li>Kuota Unlimited</li><li>Wifi Modem</li><li>Up to 5 Gadged</li><li>Include TV - Lokal Channel dan FTA</li></ul>",
                    'harga' => '225000',
                    'jenis_layanan' => 'tv'
                ]);
            Layanan::create(
                [
                    'Title' => 'TV Extream Premium 83 Channel',
                    'slug' => 'tv-xtream-premium-83-channel',
                    'benefit' => "<ul><li>Kuota Unlimited</li><li>Wifi Modem</li><li>Up to 5 Gadged</li><li>Include TV - Lokal Channel dan FTA</li></ul>",
                    'harga' => '360000',
                    'jenis_layanan' => 'tv'
                ]);
            Layanan::create(
                [
                    'Title' => 'Telephony 50K',
                    'slug' => 'telephony-50k',
                    'benefit' => "<ul><li>Gratis Abodemen</li><li>100 Menit/Bulan ke PSTN dan Selular tujuan Lokal dan SLJJ</li><li>Tarif Rp.825.-/menit</li><li>Credit Limit Rp.100.000,-/Bulan</li></ul>",
                    'harga' => '225000',
                    'jenis_layanan' => 'telephony'
                ]);
            Layanan::create(
                [
                    'Title' => 'Telephony 100K',
                    'slug' => 'telephony-100k',
                    'benefit' => "<ul><li>Gratis Abodemen</li><li>200 Menit/Bulan ke PSTN dan Selular tujuan Lokal dan SLJJ</li><li>Tarif Rp.825.-/menit</li><li>Credit Limit Rp.150.000,-/Bulan</li></ul>",
                    'harga' => '225000',
                    'jenis_layanan' => 'telephony'
                ]);
            Layanan::create(
                [
                    'Title' => 'Telephony 150K',
                    'slug' => 'telephony-150k',
                    'benefit' => "<ul><li>Gratis Abodemen</li><li>100 Menit/Bulan ke PSTN dan Selular tujuan Lokal dan SLJJ</li><li>Tarif Rp.825.-/menit</li><li>Credit Limit Rp.200.000,-/Bulan</li></ul>",
                    'harga' => '225000',
                    'jenis_layanan' => 'telephony'
                ]);
            Layanan::create(
                [
                    'Title' => 'Biaya Pemasangan',
                    'slug' => 'biaya-pemasangan',
                    'benefit' => "",
                    'harga' => '500000',
                    'jenis_layanan' => ''
                ]);

        \App\Models\Order::factory(10)->create();
    }
}
