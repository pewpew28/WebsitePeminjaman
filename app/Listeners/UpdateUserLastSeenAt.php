<?php

namespace App\Listeners;

use App\Events\UserActivityDetected;
use Carbon\Carbon;
use Spatie\Activitylog\Facades\Activity; // Import Activity Facade
use Illuminate\Contracts\Queue\ShouldQueue; // Opsional: jika ingin antrekan listener
use Illuminate\Queue\InteractsWithQueue;

class UpdateUserLastSeenAt implements ShouldQueue // Opsional: Tambahkan ShouldQueue jika ingin antrekan
{
    use InteractsWithQueue; // Opsional: Tambahkan InteractsWithQueue jika menggunakan ShouldQueue

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserActivityDetected $event): void
    {
        $user = $event->user;
        $request = $event->request;

        $oldLastSeenAt = $user->last_seen_at; // Simpan nilai lama sebelum update

        // Perbarui kolom last_seen_at dengan waktu saat ini
        $user->update(['last_seen_at' => Carbon::now()]);

        // Buat log aktivitas hanya jika last_seen_at benar-benar berubah
        // atau jika ini adalah update pertama kali (null ke non-null)
        if ($oldLastSeenAt === null || $oldLastSeenAt->diffInMinutes(Carbon::now()) >= 1) {
             Activity::forEvent('user_activity')
                ->performedOn($user)
                ->causedBy($user)
                ->withProperties([
                    'old_last_seen_at' => $oldLastSeenAt ? $oldLastSeenAt->toDateTimeString() : null,
                    'new_last_seen_at' => $user->last_seen_at->toDateTimeString(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ])
                ->log('User last seen at updated via event.');
        }
    }
}