<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function Stats()
    {
        $totalUsers = User::count();
        $totalLoanProducts = LoanProduct::count();
        $totalLoanApplications = Loan::count();
        $totalPendingLoans = Loan::where('status', 'pending')->count();

        $recentLoans = Loan::with(['user', 'loanProduct', 'loanScore'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'total_users' => $totalUsers,
            'total_loan_products' => $totalLoanProducts,
            'total_loan_applications' => $totalLoanApplications,
            'total_pending_loans' => $totalPendingLoans,
            'recent_loans' => $recentLoans,
        ]);
    }
}
