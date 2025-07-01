<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Loan extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'nasabah_id',
        'loan_amount',
        'interest_rate',
        'loan_term',
        'term_unit',
        'start_date',
        'end_date',
        'status',
        'approved_by',
        'disbursement_date',
        'fine_rate',
        'fine_unit',
        'total_principal_paid',
        'total_interest_paid',
        'total_fines_paid',
        'total_amount_with_interest',
        'remaining_principal',
        'remaining_interest',
        'remaining_fines',
        'is_refinanced',
        'original_loan_id',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'interest_rate' => 'decimal:4',
        'start_date' => 'date',
        'end_date' => 'date',
        'disbursement_date' => 'date',
        'fine_rate' => 'decimal:4',
        'total_principal_paid' => 'decimal:2',
        'total_interest_paid' => 'decimal:2',
        'total_fines_paid' => 'decimal:2',
        'total_amount_with_interest' => 'decimal:2',
        'remaining_principal' => 'decimal:2',
        'remaining_interest' => 'decimal:2',
        'remaining_fines' => 'decimal:2',
        'is_refinanced' => 'boolean',
    ];

    /**
     * Relasi: Pinjaman dimiliki oleh satu Customer.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class);
    }

    /**
     * Relasi: Pinjaman disetujui oleh satu User (Admin/Finance).
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relasi: Pinjaman memiliki banyak Cicilan.
     */
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    /**
     * Relasi: Pinjaman bisa menjadi hasil refinance dari pinjaman lain.
     */
    public function originalLoan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'original_loan_id');
    }

    /**
     * Relasi: Pinjaman bisa memiliki pinjaman hasil refinance.
     */
    public function refinancedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'original_loan_id');
    }

    /**
     * Relasi: Pinjaman bisa memiliki banyak Tugas Collector terkait.
     */
    public function collectorTasks(): HasMany
    {
        return $this->hasMany(CollectorTask::class);
    }

    /**
     * Konfigurasi opsi logging untuk model Loan.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
