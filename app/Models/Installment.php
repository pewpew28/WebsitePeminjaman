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
        'remaining_amount', // Kolom baru untuk sisa pembayaran
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
        'remaining_amount' => 'decimal:2', // Cast untuk kolom baru
    ];

    // Atau bisa menggunakan accessor jika tidak ingin menyimpan di database
    protected $appends = ['calculated_remaining_amount'];

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
     * Accessor untuk menghitung sisa pembayaran secara dinamis.
     */
    public function getCalculatedRemainingAmountAttribute(): float
    {
        return max(0, $this->total_due_amount - $this->amount_paid);
    }

    /**
     * Accessor untuk mendapatkan sisa pembayaran (prioritas kolom database).
     */
    public function getRemainingAmountAttribute($value): float
    {
        // Jika ada nilai di database, gunakan itu
        if ($value !== null) {
            return (float) $value;
        }
        
        // Jika tidak ada, hitung secara dinamis
        return $this->calculated_remaining_amount;
    }

    /**
     * Mutator untuk otomatis menghitung sisa pembayaran saat amount_paid berubah.
     */
    public function setAmountPaidAttribute($value): void
    {
        $this->attributes['amount_paid'] = $value;
        
        // Otomatis hitung remaining_amount
        if (isset($this->attributes['total_due_amount'])) {
            $this->attributes['remaining_amount'] = max(0, $this->attributes['total_due_amount'] - $value);
        }
    }

    /**
     * Method untuk update pembayaran dengan menghitung sisa otomatis.
     */
    public function updatePayment(float $paidAmount, ?string $paymentMethod = null, ?int $paidByUserId = null): void
    {
        $this->amount_paid = $paidAmount;
        $this->remaining_amount = max(0, $this->total_due_amount - $paidAmount);
        
        if ($paymentMethod) {
            $this->payment_method = $paymentMethod;
        }
        
        if ($paidByUserId) {
            $this->paid_by_user_id = $paidByUserId;
        }
        
        $this->payment_date = now();
        
        // Update status berdasarkan sisa pembayaran
        $this->updateStatus();
        
        $this->save();
    }

    /**
     * Method untuk mengupdate status berdasarkan sisa pembayaran.
     */
    public function updateStatus(): void
    {
        $remaining = $this->remaining_amount ?? $this->calculated_remaining_amount;
        
        if ($remaining <= 0) {
            $this->status = 'paid'; // Lunas
        } elseif ($this->amount_paid > 0) {
            $this->status = 'partial'; // Sebagian terbayar
        } else {
            $this->status = 'pending'; // Belum terbayar
        }
    }

    /**
     * Scope untuk mencari cicilan dengan sisa pembayaran.
     */
    public function scopeWithRemainingAmount($query)
    {
        return $query->where('remaining_amount', '>', 0)
                    ->orWhereRaw('(total_due_amount - amount_paid) > 0');
    }

    /**
     * Scope untuk mencari cicilan yang sudah lunas.
     */
    public function scopeFullyPaid($query)
    {
        return $query->where('remaining_amount', '<=', 0)
                    ->orWhereRaw('(total_due_amount - amount_paid) <= 0');
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