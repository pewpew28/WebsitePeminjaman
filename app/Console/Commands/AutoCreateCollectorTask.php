<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Installment;
use App\Models\CollectorTask;
use App\Models\Collector;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoCreateCollectorTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collector:auto-create-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically create collector tasks for installments due today';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting auto collector task creation...');
        
        // Ambil tanggal hari ini
        $today = Carbon::today();
        
        // Ambil installment yang due date nya hari ini dan belum dibayar
        $dueInstallments = Installment::where('due_date', $today)
            ->whereIn('status', ['pending', 'overdue']) // atau status yang sesuai
            ->with(['loan.nasabah']) // eager load relationship
            ->get();
        
        $this->info("Found {$dueInstallments->count()} installments due today");
        
        $createdTasks = 0;
        
        foreach ($dueInstallments as $installment) {
            // Cek apakah sudah ada collector task untuk installment ini
            $existingTask = CollectorTask::where('loan_id', $installment->loan_id)
                ->where('due_date', $today)
                ->first();
            
            if (!$existingTask) {
                // Ambil collector yang available (bisa disesuaikan logika assignment)
                $collector = $this->getAvailableCollector();
                
                if ($collector) {
                    // Buat collector task baru
                    CollectorTask::create([
                        'collector_id' => $collector->id,
                        'nasabah_id' => $installment->loan->nasabah_id,
                        'loan_id' => $installment->loan_id,
                        'assigned_date' => $today,
                        'due_date' => $today,
                        'status' => 'assigned', // atau status default yang sesuai
                        'notes' => "Auto-created task for installment #{$installment->installment_number} due today",
                        'amount_collected_during_task' => 0,
                    ]);
                    
                    $createdTasks++;
                    
                    $this->info("Created collector task for loan ID: {$installment->loan_id}");
                } else {
                    $this->warn("No available collector found for loan ID: {$installment->loan_id}");
                }
            } else {
                $this->info("Collector task already exists for loan ID: {$installment->loan_id}");
            }
        }
        
        $this->info("Auto collector task creation completed. Created {$createdTasks} new tasks.");
        
        // Log aktivitas
        Log::info('Auto collector task creation completed', [
            'date' => $today->toDateString(),
            'due_installments_count' => $dueInstallments->count(),
            'created_tasks_count' => $createdTasks,
        ]);
        
        return Command::SUCCESS;
    }
    
    /**
     * Get available collector for assignment
     * Customize this method based on your business logic
     */
    private function getAvailableCollector()
    {
        // Contoh sederhana: ambil collector dengan task paling sedikit hari ini
        $collectors = User::where('role','collector')->withCount(['collectorTasks' => function($query) {
            $query->where('assigned_date', Carbon::today())
                  ->whereIn('status', ['assigned', 'in_progress']);
        }])->get();
        
        // Kembalikan collector dengan task paling sedikit
        return $collectors->sortBy('collector_tasks_count')->first();
        
        // Atau bisa menggunakan logika round-robin, berdasarkan area, dll
    }
}