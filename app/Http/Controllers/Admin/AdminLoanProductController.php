<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateLoanRequest;
use App\Http\Requests\Admin\UpdateLoanRequest;
use App\Services\Admin\LoanService;
use App\Services\Admin\UserLoanService;
use Illuminate\Http\Request;

class AdminLoanProductController extends Controller
{
    public function store(LoanService $loan, CreateLoanRequest $request)
    {
        return $loan->storeLoan($request);
    }

    public function index(LoanService $loan)
    {
        return $loan->allLoan();
    }

    public function view(LoanService $loan, $id)
    {
        return $loan->singleLoan($id);
    }

    public function update(LoanService $loan, UpdateLoanRequest $request, $id)
    {
        return $loan->UpdateLoan($request, $id);
    }

    public function AllUserLoan(UserLoanService $loan)
    {
        return $loan->AllUserAppliedLoan();
    }

    public function ViewSingleUserLoan(UserLoanService $loan, $id)
    {
        return $loan->SingleUserAppliedLoan($id);
    }

    public function ApproveLoan(UserLoanService $loan, $id)
    {
        return $loan->Approve($id);
    }

    public function RejectLoan(UserLoanService $loan, $id)
    {
        return $loan->Reject($id);
    }
}
