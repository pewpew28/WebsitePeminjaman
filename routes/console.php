<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Installment;
use App\Models\CollectorTask;
use App\Models\Collector;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('collector:auto-create-tasks', function () {
    $this->info('Starting auto collector task creation...');
    
    // Ambil tanggal hari ini
    $today = Carbon::today();
    
    // Ambil installment yang due date nya hari ini dan belum dibayar
    $dueInstallments = Installment::where('due_date', $today)
        ->whereIn('status', ['pending', 'overdue']) // sesuaikan dengan status yang ada
        ->with(['loan.nasabah']) // pastikan relationship sudah ada
        ->get();
    
    $this->info("Found {$dueInstallments->count()} installments due today");
    
    $createdTasks = 0;
    
    foreach ($dueInstallments as $installment) {
        // Cek apakah sudah ada collector task untuk installment ini
        $existingTask = CollectorTask::where('loan_id', $installment->loan_id)
            ->where('due_date', $today)
            ->first();
        
        if (!$existingTask) {
            // Ambil collector yang available
            $collector = $this->getAvailableCollector();
            
            if ($collector) {
                // Buat collector task baru
                CollectorTask::create([
                    'collector_id' => $collector->id,
                    'nasabah_id' => $installment->loan->nasabah_id,
                    'loan_id' => $installment->loan_id,
                    'assigned_date' => $today,
                    'due_date' => $today,
                    'status' => 'assigned', // sesuaikan dengan enum status yang ada
                    'notes' => "Auto-created task for installment #{$installment->installment_number} due today",
                    'amount_collected_during_task' => 0,
                ]);
                
                $createdTasks++;
                $this->info("✓ Created collector task for loan ID: {$installment->loan_id}");
            } else {
                $this->warn("⚠ No available collector found for loan ID: {$installment->loan_id}");
            }
        } else {
            $this->line("→ Collector task already exists for loan ID: {$installment->loan_id}");
        }
    }
    
    $this->info("🎉 Auto collector task creation completed!");
    $this->info("📊 Summary: Created {$createdTasks} new tasks out of {$dueInstallments->count()} due installments");
    
    // Log aktivitas
    Log::info('Auto collector task creation completed', [
        'date' => $today->toDateString(),
        'due_installments_count' => $dueInstallments->count(),
        'created_tasks_count' => $createdTasks,
    ]);
    
})->purpose('Automatically create collector tasks for installments due today');

// Helper function untuk mencari collector yang available
function getAvailableCollector()
{
    // Ambil collector dengan task paling sedikit hari ini
    $collectors = User::where('role','collector')->withCount(['collectorTasks' => function($query) {
        $query->where('assigned_date', Carbon::today())
              ->whereIn('status', ['assigned', 'in_progress']);
    }])->get();
    
    // Kembalikan collector dengan task paling sedikit
    return $collectors->sortBy('collector_tasks_count')->first();
    
    // Alternatif logika assignment:
    // 1. Berdasarkan area/wilayah
    // 2. Round-robin
    // 3. Berdasarkan kapasitas collector
}