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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade')->comment('ID nasabah yang mengajukan pinjaman');
            $table->foreignId('collector_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->decimal('loan_amount', 15, 2)->comment('Jumlah pokok pinjaman');
            $table->decimal('interest_rate', 5, 4)->comment('Tingkat bunga per periode (misal: 0.05 untuk 5%)');
            $table->integer('loan_term')->comment('Jangka waktu pinjaman');
            $table->string('term_unit')->default('months')->comment('Satuan jangka waktu (days, weeks, months, years)');
            $table->date('start_date')->comment('Tanggal mulai pinjaman');
            $table->date('end_date')->comment('Tanggal jatuh tempo pinjaman terakhir');
            $table->string('status')->default('pending')->comment('Status pinjaman (pending, approved, rejected, active, completed, overdue, refinanced)');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->comment('User (Admin/Finance) yang menyetujui pinjaman');
            $table->date('disbursement_date')->nullable()->comment('Tanggal pencairan dana');
            $table->decimal('fine_rate', 5, 4)->default(0.00)->comment('Tingkat denda per periode (misal: 0.01 untuk 1%)');
            $table->string('fine_unit')->default('daily')->comment('Satuan denda (daily, monthly)');
            $table->decimal('total_principal_paid', 15, 2)->default(0.00)->comment('Total pokok yang sudah dibayar');
            $table->decimal('total_interest_paid', 15, 2)->default(0.00)->comment('Total bunga yang sudah dibayar');
            $table->decimal('total_fines_paid', 15, 2)->default(0.00)->comment('Total denda yang sudah dibayar');
            $table->decimal('total_amount_with_interest', 15, 2)->comment('Total keseluruhan pinjaman termasuk bunga');
            $table->decimal('remaining_principal', 15, 2)->comment('Sisa pokok pinjaman');
            $table->decimal('remaining_interest', 15, 2)->comment('Sisa bunga pinjaman');
            $table->decimal('remaining_fines', 15, 2)->default(0.00)->comment('Sisa denda pinjaman');
            $table->boolean('is_refinanced')->default(false)->comment('Apakah pinjaman ini hasil refinance');
            $table->foreignId('original_loan_id')->nullable()->constrained('loans')->onDelete('set null')->comment('ID pinjaman asli jika ini adalah hasil refinance');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
