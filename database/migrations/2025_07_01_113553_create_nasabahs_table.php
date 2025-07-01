<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nasabahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('User (Admin/Finance) yang mendaftarkan nasabah ini');
            $table->string('name')->comment('Nama lengkap nasabah');
            $table->string('email')->nullable()->unique()->comment('Alamat email nasabah');
            $table->string('phone_number')->unique()->comment('Nomor telepon nasabah');
            $table->text('address')->comment('Alamat lengkap nasabah');
            $table->string('id_card_number')->unique()->comment('Nomor KTP nasabah');
            $table->date('date_of_birth')->comment('Tanggal lahir nasabah');
            $table->string('gender')->comment('Jenis kelamin nasabah (Laki-laki/Perempuan)');
            $table->string('occupation')->comment('Pekerjaan nasabah');
            $table->decimal('monthly_income', 15, 2)->comment('Pendapatan bulanan nasabah');
            $table->string('status')->default('active')->comment('Status nasabah (active, inactive, blacklist)');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nasabahs');
    }
};
