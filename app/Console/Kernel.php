<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Jalankan auto collector task setiap jam 12 malam
        $schedule->command('collector:auto-create-tasks')
                 ->dailyAt('00:00') // Jam 12 malam
                 ->withoutOverlapping() // Mencegah command berjalan bersamaan
                 ->runInBackground() // Jalankan di background
                 ->sendOutputTo(storage_path('logs/collector-auto-tasks.log')); // Simpan log
                 
        // Alternatif jam 1 malam jika mau:
        // ->dailyAt('01:00')
        
        // Bisa juga ditambahkan backup schedule jika yang pertama gagal
        $schedule->command('collector:auto-create-tasks')
                 ->dailyAt('00:30') // 30 menit setelah schedule pertama
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->when(function () {
                     // Hanya jalankan jika belum ada task yang dibuat hari ini
                     $today = \Carbon\Carbon::today();
                     $taskCount = \App\Models\CollectorTask::where('assigned_date', $today)->count();
                     return $taskCount == 0; // Backup jika schedule pertama gagal
                 });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}