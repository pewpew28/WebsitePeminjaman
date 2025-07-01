<?php

namespace Database\Seeders;

use App\Models\CollectorTask;
use App\Models\Nasabah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectorTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $collectorUsers = User::where('role', 'collector')->get();
        $customers = Nasabah::all();

        foreach ($collectorUsers as $collector) {
            // Buat beberapa tugas untuk setiap kolektor
            for ($i = 0; $i < fake()->numberBetween(5, 15); $i++) {
                $customer = $customers->random();
                $loan = $customer->loans->random(); // Ambil salah satu pinjaman customer
                // Pastikan assignedDate adalah instance Carbon
                $assignedDate = Carbon::parse(fake()->dateTimeBetween('-1 month', 'now'));
                $dueDate = (clone $assignedDate)->addDays(fake()->numberBetween(1, 14));

                $status = fake()->randomElement(['assigned', 'pending', 'done', 'failed']);
                $actualVisitDate = null;
                $amountCollected = 0;

                if ($status === 'done') {
                    // Pastikan actualVisitDate adalah instance Carbon
                    $actualVisitDate = Carbon::parse(fake()->dateTimeBetween($assignedDate, $dueDate));
                    $amountCollected = fake()->randomFloat(2, 100000, 1000000);
                } elseif ($status === 'failed') {
                    // Pastikan actualVisitDate adalah instance Carbon
                    $actualVisitDate = Carbon::parse(fake()->dateTimeBetween($assignedDate, $dueDate));
                }

                CollectorTask::create([
                    'collector_id' => $collector->id,
                    'nasabah_id' => $customer->id,
                    'loan_id' => $loan ? $loan->id : null, // Bisa null jika tugas umum
                    'assigned_date' => $assignedDate,
                    'due_date' => $dueDate,
                    'status' => $status,
                    'notes' => fake()->paragraph(),
                    'visit_confirmation_qr_data' => fake()->uuid(), // Contoh data QR
                    'actual_visit_date' => $actualVisitDate,
                    'amount_collected_during_task' => $amountCollected,
                ]);
            }
        }
    }
}
