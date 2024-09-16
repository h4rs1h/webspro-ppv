<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelanggansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lantai_id');
            $table->foreignId('tower_id');
            $table->string('unitPelanggan')->nullable();
            $table->string('NoIdentitas');
            $table->string('Alamat');
            $table->string('Notelp');
            $table->string('Hp');
            $table->integer('nounit');
            $table->boolean('is_pemilik')->default(false);
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
        Schema::dropIfExists('pelanggan');
    }
}
