<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keranjang', function(Blueprint $table) {
            $table->id();
            $table->string('produk_id');
            $table->string('stock');
            $table->string('status');
            $table->string('transaksi_id')->default(0)->nullable();
            $table->integer('kasir_id');
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
        //
    }
};
