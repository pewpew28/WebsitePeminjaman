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

class CollectorTask extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    use LogsActivity;
    
    protected $fillable = [
        'collector_id',
        'nasabah_id',
        'installment_id',
        'assigned_date',
        'due_date',
        'status',
        'notes',
        'actual_visit_date',
        'amount_collected_during_task',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'due_date' => 'date',
        'actual_visit_date' => 'datetime',
        'amount_collected_during_task' => 'decimal:2',
    ];

    /**
     * Relasi: Tugas Collector ditugaskan kepada satu User (Collector).
     */
    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    /**
     * Relasi: Tugas Collector terkait dengan satu Customer.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class);
    }

    /**
     * Relasi: Tugas Collector bisa terkait dengan satu Pinjaman.
     */
    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('bukti_bayar')
            ->singleFile(); // Asumsi hanya satu gambar profil
    }
    /**
     * Konfigurasi opsi logging untuk model CollectorTask.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // Log semua atribut yang ada di $fillable
            ->logOnlyDirty() // Hanya log atribut yang berubah
            ->dontSubmitEmptyLogs(); // Jangan log jika tidak ada perubahan
    }
}
