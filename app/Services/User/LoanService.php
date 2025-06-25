<?php

namespace App\Services\User;

use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\RepaymentSchedule;
use App\Services\LoanScoringService;
use Exception;

class LoanService
{
    public function UserLoanApply($request)
    {

        try {
            $product  = LoanProduct::find($request->loan_product_id);
            if (!$product) {
                return response()->json(['error' => 'loan product not found'], 404);
            }
            if ($request->amount > $product->max_amount) {
                return response()->json(['error' => 'Amount exceeds max allowed'], 400);
            }


            $existingLoan = Loan::where('user_id', auth()->id())
                ->where('loan_product_id', $product->id)
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existingLoan) {
                return response()->json([
                    'error' => 'You already have a loan that is pending or active for this product.',
                ], 400);
            }

            $interestRate =   $product->interest_rate;

            $durationDays =  $product->duration_days;

            $interest = ($request->amount * $interestRate) / 100;
            $repaymentDue = $request->amount + $interest;
            $loan = Loan::create([
                'user_id' => auth()->id(),
                'loan_product_id' => $product->id,
                'amount' => $request->amount,
                'interest_rate' => $interestRate,
                'duration_days' => $durationDays,
                'repayment_due' => $repaymentDue,
                'purpose' => $request->purpose,
            ]);
            app(LoanScoringService::class)->score($loan);
            $dailyAmount = round($repaymentDue / $durationDays, 2);
            $today = now();

            for ($i = 1; $i <= $durationDays; $i++) {
                RepaymentSchedule::create([
                    'loan_id' => $loan->id,
                    'due_date' => $today->copy()->addDays($i),
                    'amount_due' => $dailyAmount,
                    'status' => 'pending',
                ]);
            }

            return response()->json([
                'message' => 'Loan submitted with payment schedule',
                'loan' => $loan->load('repaymentSchedules')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "loan application failed",
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function UserLoans()
    {
        $loans = Loan::with('loanProduct')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json($loans);
    }

    public function ViewUserLoans($id)
    {
        $loan = Loan::with(['loanProduct', 'loanScore', 'repaymentSchedules'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return response()->json($loan);
    }
}
