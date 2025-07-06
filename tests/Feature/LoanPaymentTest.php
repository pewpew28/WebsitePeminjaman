<?php

namespace Tests\Feature;

use App\Models\Installment;
use App\Models\Loan;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanPaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_marks_loan_as_completed_when_all_installments_paid()
    {
        $collector = User::factory()->create([
            'role' => 'collector', // ✅ pastikan role collector
        ]);

        $nasabah = Nasabah::factory()->create();
        $loan = Loan::factory()->create([
            'nasabah_id' => $nasabah->id,
            'status' => 'active',
        ]);

        $installments = Installment::factory()->count(2)->create([
            'loan_id' => $loan->id,
            'remaining_amount' => 1000,
            'status' => 'pending',
            'amount_paid' => 0,
            'total_due_amount' => 1000,
        ]);

        $payload = [
            'nasabah_id' => $nasabah->id,
            'paid_amount' => 2000,
            'payment_method' => 'cash',
            'installment_ids' => $installments->pluck('id')->toArray(),
        ];

        $this->actingAs($collector)
            ->postJson(route('collector.payments.store'), $payload)
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertEquals('completed', $loan->fresh()->status);
    }
}
