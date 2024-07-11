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
        Schema::create('surat_masuk', function (Blueprint $table) {
            // Primary key
            $table->id('id_suratmasuk'); 

            // Other fields
            $table->string('no_surat')->unique();
            $table->date('tanggal');
            $table->string('lampiran');
            $table->string('file_surat');

            // Foreign key dari tabel jenis_surat
            $table->unsignedBigInteger('id_jenis');
            $table->unsignedBigInteger('id_jabatan');
            $table->unsignedBigInteger('id_asal_surat');
            $table->unsignedBigInteger('id_sifat');
            $table->unsignedBigInteger('id_pengguna');

            // Foreign key constraints
            $table->foreign('id_jenis')->references('id')->on('jenis_surat')->onDelete('cascade');
            $table->foreign('id_jabatan')->references('id')->on('jabatan')->onDelete('cascade');
            $table->foreign('id_asal_surat')->references('id')->on('asal_surat')->onDelete('cascade');
            $table->foreign('id_sifat')->references('id')->on('sifat_surat')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_masuk');
    }
};
