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
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade')->comment('ID pinjaman terkait');
            $table->integer('installment_number')->comment('Nomor cicilan (misal: 1, 2, 3)');
            $table->date('due_date')->comment('Tanggal jatuh tempo cicilan');
            $table->decimal('principal_amount', 15, 2)->comment('Jumlah pokok cicilan');
            $table->decimal('interest_amount', 15, 2)->comment('Jumlah bunga cicilan');
            $table->decimal('fine_amount', 15, 2)->default(0.00)->comment('Jumlah denda yang dikenakan pada cicilan ini');
            $table->decimal('total_due_amount', 15, 2)->comment('Total jumlah yang harus dibayar (pokok + bunga + denda)');
            $table->decimal('amount_paid', 15, 2)->default(0.00)->comment('Jumlah yang sudah dibayar untuk cicilan ini');
            $table->decimal('remaining_amount', 15, 2)->default(0)->comment('sisa Pembayaran');
            $table->dateTime('payment_date')->nullable()->comment('Tanggal dan waktu pembayaran dilakukan');
            $table->string('status')->default('unpaid')->comment('Status cicilan (unpaid, paid, partially_paid, overdue)');
            $table->foreignId('paid_by_user_id')->nullable()->constrained('users')->onDelete('set null')->comment('User (Collector/Finance) yang menerima pembayaran');
            $table->string('payment_method')->nullable()->comment('Metode pembayaran (cash, transfer)');
            $table->text('notes')->nullable()->comment('Catatan pembayaran');
            $table->foreignId('collector_id')->nullable()->constrained('users')->onDelete('cascade');
            // Untuk bukti pembayaran (foto/transfer), akan menggunakan Spatie MediaLibrary
            // yang akan berelasi secara polimorfik dengan model Installment.
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
