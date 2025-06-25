<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoanApplicationRequest;
use App\Services\User\LoanService;
use Illuminate\Http\Request;

class UserLoanController extends Controller
{
    public function LoanApplication(LoanService $loan, LoanApplicationRequest $request)
    {
        return $loan->UserLoanApply($request);
    }

    public function AllLoans(LoanService $loan)
    {
        return $loan->UserLoans();
    }

    public function viewLoan(LoanService $loan, $id)
    {
        return $loan->ViewUserLoans($id);
    }
}
