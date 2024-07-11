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
        Schema::create('disposisi', function (Blueprint $table) {
            // Primary key
            $table->id('id_disposisi'); 

            // Other fields
            $table->string('no_disposisi')->unique();
            $table->date('tanggal');
            $table->string('sifat_disposisi');
            $table->string('jenis_disposisi');
            $table->string('perihal');
            $table->string('instruksi');
            $table->string('keterangan');

            // Foreign key dari tabel jenis_surat dan asal_surat
            $table->unsignedBigInteger('id_surat_masuk');
            $table->unsignedBigInteger('id_asal_surat');

            // Foreign key constraints
            $table->foreign('id_surat_masuk')->references('id')->on('surat_masuk')->onDelete('cascade');
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
        Schema::dropIfExists('disposisi');
    }
};
