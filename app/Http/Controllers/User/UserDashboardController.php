<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function Stats()
    {
        $user = Auth::user();

        $totalLoans = Loan::where('user_id', $user->id)->count();
        $pendingLoans = Loan::where('user_id', $user->id)->where('status', 'pending')->count();
        $repaidLoans = Loan::where('user_id', $user->id)->where('status', 'paid')->count();
        $activeLoans = Loan::where('user_id', $user->id)->whereIn('status', ['approved'])->count();

        $totalPaid = Loan::where('user_id', $user->id)
            ->with('repaymentSchedules')
            ->get()
            ->flatMap->repaymentSchedules
            ->where('status', 'paid')
            ->sum('amount_due');

        $totalOutstanding = Loan::where('user_id', $user->id)
            ->with('repaymentSchedules')
            ->get()
            ->flatMap->repaymentSchedules
            ->where('status', '!=', 'paid')
            ->sum('amount_due');

        $recentLoans = Loan::with('loanProduct')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'total_loans' => $totalLoans,
            'pending_loans' => $pendingLoans,
            'active_loans' => $activeLoans,
            'repaid_loans' => $repaidLoans,
            'total_paid_amount' => $totalPaid,
            'total_outstanding_balance' => $totalOutstanding,
            'recent_loans' => $recentLoans,
        ]);
    }
}
