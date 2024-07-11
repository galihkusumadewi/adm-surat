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
        Schema::create('surat_keluar', function (Blueprint $table) {
            // Primary key
            $table->id('id_suratkeluar'); 

            // Other fields
            $table->string('no_surat')->unique();
            $table->date('tgl_surat');
            $table->string('perihal');
            $table->string('lampiran');
            $table->string('keterangan');
            $table->string('file_surat');

            // Foreign key dari tabel jenis_surat dan asal_surat
            $table->unsignedBigInteger('id_jenis');
            $table->unsignedBigInteger('id_asal_surat');

            // Foreign key constraints
            $table->foreign('id_jenis')->references('id')->on('jenis_surat')->onDelete('cascade');
            $table->foreign('id_asal_surat')->references('id')->on('asal_surat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_keluar');
    }
};
