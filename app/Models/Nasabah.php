<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Nasabah extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_number',
        'address',
        'id_card_number',
        'date_of_birth',
        'gender',
        'occupation',
        'monthly_income',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'monthly_income' => 'decimal:2',
    ];

    /**
     * Relasi: Customer dimiliki oleh satu User (Admin/Finance).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Customer bisa memiliki banyak Pinjaman.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Relasi: Customer bisa memiliki banyak Tugas Collector.
     */
    public function collectorTasks(): HasMany
    {
        return $this->hasMany(CollectorTask::class);
    }

    /**
     * Konfigurasi opsi logging untuk model Customer.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
