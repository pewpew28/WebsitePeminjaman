<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Installment extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    use LogsActivity;

    protected $fillable = [
        'loan_id',
        'installment_number',
        'due_date',
        'principal_amount',
        'interest_amount',
        'fine_amount',
        'total_due_amount',
        'amount_paid',
        'payment_date',
        'status',
        'paid_by_user_id',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'payment_date' => 'datetime',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'fine_amount' => 'decimal:2',
        'total_due_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Relasi: Cicilan milik satu Pinjaman.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Relasi: Cicilan dibayar oleh satu User (Collector/Finance).
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by_user_id');
    }

    /**
     * Register media collections for payment proofs.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('payment_proofs');
    }

     /**
     * Konfigurasi opsi logging untuk model Installment.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
