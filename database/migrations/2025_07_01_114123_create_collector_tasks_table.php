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
        Schema::create('collector_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collector_id')->constrained('users')->onDelete('cascade')->comment('ID user Collector yang ditugaskan');
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade')->comment('ID nasabah yang akan dikunjungi');
            $table->foreignId('installment_id')->nullable()->constrained('installments')->onDelete('set null')->comment('ID pinjaman terkait (bisa null jika tugas umum)');
            $table->date('assigned_date')->comment('Tanggal tugas diberikan');
            $table->date('due_date')->comment('Tanggal jatuh tempo tugas');
            $table->string('status')->default('assigned')->comment('Status tugas (assigned, pending, done, failed, rescheduled)');
            $table->text('notes')->nullable()->comment('Catatan tugas');
            $table->string('visit_confirmation_qr_data')->nullable()->comment('Data untuk QR Code konfirmasi kunjungan');
            $table->dateTime('actual_visit_date')->nullable()->comment('Tanggal dan waktu kunjungan aktual');
            $table->decimal('amount_collected_during_task', 15, 2)->default(0.00)->comment('Jumlah uang yang berhasil dikumpulkan selama tugas ini');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collector_tasks');
    }
};
