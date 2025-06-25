<?php

namespace App\Services\Admin;

use App\Models\Loan;

class UserLoanService
{


    public function AllUserAppliedLoan()
    {

        $loans = Loan::with(['user', 'loanProduct', 'loanScore'])
            ->latest()
            ->get();

        return response()->json($loans);
    }

    public function SingleUserAppliedLoan($id)
    {
        $loan = Loan::with(['user', 'loanProduct', 'loanScore', 'repaymentSchedules'])
            ->findOrFail($id);

        return response()->json($loan);
    }

    public function Approve($id)
    {

        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return response()->json(['message' => 'Loan is already processed'], 400);
        }


        $loan->update(['status' => 'approved']);

        return response()->json(['message' => 'Loan approved successfully']);
    }

    public function Reject($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return response()->json(['message' => 'Loan is already processed'], 400);
        }

        $loan->update(['status' => 'rejected']);

        return response()->json(['message' => 'Loan rejected successfully']);
    }
}
