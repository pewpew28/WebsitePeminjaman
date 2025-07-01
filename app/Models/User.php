<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity; // Import trait LogsActivity
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia;
    use SoftDeletes;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'address',
        'role',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: User bisa mendaftarkan banyak Customer.
     */
    public function nasabahs(): HasMany
    {
        return $this->hasMany(Nasabah::class, 'user_id');
    }

    /**
     * Relasi: User bisa menyetujui banyak Pinjaman.
     */
    public function approvedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'approved_by');
    }

    /**
     * Relasi: User bisa menerima banyak Pembayaran Cicilan.
     */
    public function receivedInstallments(): HasMany
    {
        return $this->hasMany(Installment::class, 'paid_by_user_id');
    }

    /**
     * Relasi: User bisa memiliki banyak Tugas Collector (jika perannya Collector).
     */
    public function collectorTasks(): HasMany
    {
        return $this->hasMany(CollectorTask::class, 'collector_id');
    }

    /**
     * Register media collections for user profile pictures.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_pictures')
            ->singleFile(); // Asumsi hanya satu gambar profil
    }

    /**
     * Konfigurasi opsi logging untuk model User.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
