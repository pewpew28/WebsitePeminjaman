<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Setting extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Cast the value attribute to appropriate type if needed.
     * For example, if 'interest_rate' is stored as string but used as float.
     */
    protected $casts = [
        // 'value' => 'float', // Contoh casting jika value selalu float
    ];
    /**
     * Konfigurasi opsi logging untuk model Setting.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
