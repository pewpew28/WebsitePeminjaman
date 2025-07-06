<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\CollectorTask;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectorPaymentController extends Controller
{
    public function store(Request $request)
    {
        // Decode installment_ids jika string JSON
        if (is_string($request->installment_ids)) {
            $decoded = json_decode($request->installment_ids, true);
            $request->merge(['installment_ids' => $decoded]);
        }

        $validated = $request->validate([
            'nasabah_id'        => 'required|exists:nasabahs,id',
            'paid_amount'       => 'required|numeric|min:1',
            'payment_method'    => 'required|string',
            'notes'             => 'nullable|string|max:1000',
            'installment_ids'   => 'required|array',
            'installment_ids.*' => 'exists:installments,id',
            'payment_proof'     => 'nullable|file|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $totalPaid = $validated['paid_amount'];
            $paidInstallmentIds = [];

            $installments = Installment::whereIn('id', $validated['installment_ids'])
                ->where(function ($q) {
                    $q->where('status', '!=', 'paid');
                })
                ->orderBy('due_date')
                ->lockForUpdate()
                ->get();

            if (
                $request->hasFile('payment_proof') &&
                $request->file('payment_proof')->isValid()
            ) {
                $uploadedProof = $request->file('payment_proof');
            } else {
                $uploadedProof = null;
            }

            $firstInstallmentWithProof = null;

            foreach ($installments as $installment) {
                if ($totalPaid <= 0) break;

                $remainingToPay = max(0, $installment->total_due_amount - $installment->amount_paid);


                $payNow = min($totalPaid, $remainingToPay);
                $installment->updatePayment(
                    $installment->amount_paid + $payNow,
                    $validated['payment_method'],
                    auth()->id()
                );

                $totalPaid -= $payNow;

                if ($uploadedProof && !$firstInstallmentWithProof) {
                    $installment->addMedia($uploadedProof)
                        ->toMediaCollection('payment_proofs');

                    $firstInstallmentWithProof = $installment;
                } elseif ($uploadedProof && $firstInstallmentWithProof) {
                    $media = $firstInstallmentWithProof->getFirstMedia('payment_proofs');
                    if ($media) {
                        $media->copy($installment, 'payment_proofs');
                    }
                }

                // Tentukan status CollectorTask dari status Installment
                $taskStatus = match ($installment->status) {
                    'paid' => 'completed',
                    'partial' => 'partial',
                    default => 'pending',
                };

                CollectorTask::where('installment_id', $installment->id)
                    ->update([
                        'amount_collected_during_task' => $installment->amount_paid,
                        'status' => $taskStatus,
                        'actual_visit_date' => now(),
                        'notes' => $validated['notes'],
                    ]);

                $paidInstallmentIds[] = $installment->id;
            }

            $loan = optional($installments->first())->loan;
            if ($loan) {
                $allPaid = $loan->installments()->where('status', '!=', 'paid')->count() === 0;

                if ($allPaid) {
                    $loan->update(['status' => 'completed']);
                }
            }

            // Log activity eksplisit
            $activity = activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'nasabah_id' => $validated['nasabah_id'],
                    'paid_amount' => $validated['paid_amount'],
                    'payment_method' => $validated['payment_method'],
                    'notes' => $validated['notes'],
                    'paid_installments' => $paidInstallmentIds,
                ]);

            if ($loan) {
                $activity->performedOn($loan);
            }

            $activity->log("Collector melakukan pembayaran Rp {$validated['paid_amount']} untuk nasabah ID {$validated['nasabah_id']} dengan metode {$validated['payment_method']}");

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }
}
