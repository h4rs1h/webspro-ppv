<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderLayanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_layanan', function (Blueprint $table) {
            $table->id();
            $table->integer('no_order');
            $table->string('nomor_order',40)->nullable();
            $table->timestamp('tgl_order')->nullable();
            $table->foreignId('user_id');
            $table->timestamp('tgl_rencana_langganan')->nullable();
            $table->timestamp('tgl_mulai_langganan')->nullable();
            $table->double('deposit')->nullable();
            $table->double('biaya_pemasangan')->nullable();
            $table->double('ppn')->nullable();
            $table->double('total_tagihan');
            $table->string('status_bayar',40);
            $table->string('jenis_pembayaran',40)->nullable();
            $table->double('kodeunik')->nullable();
            $table->double('total_tagihan_transfer')->nullable();
            $table->enum('payment_status',['1','2','3','4'])->comment('1=menunggu pembayaran, 2=sudah dibayar, 3=kadaluarsa, 4=batal');
            $table->string('snap_token', 36)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_layanan');
    }
}
