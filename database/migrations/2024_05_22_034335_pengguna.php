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
        Schema::create('pengguna', function (Blueprint $table) {
            // Primary key
            $table->id('id_pengguna'); 

            // Other fields
            $table->string('nama');
            $table->string('NIK', 16)->unique();
            $table->string('alamat');
            $table->string('no_hp', 13);
            $table->string('password', 255);
            $table->string('username')->unique();

            // Foreign key dari tabel jabatan
            $table->unsignedBigInteger('id_jabatan');

            // Foreign key constraints
            $table->foreign('id_jabatan')->references('id')->on('jabatan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengguna');
    }
};
