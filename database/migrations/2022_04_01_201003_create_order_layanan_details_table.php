<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderLayananDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_layanan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_layanan_id');
            $table->foreignId('layanan_id');
            $table->integer('periode_langganan');
            $table->double('harga_layanan');
            $table->double('diskon')->nullable();
            $table->double('subtotal')->nullable();
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
        Schema::dropIfExists('order_layanan_detail');
    }
}
