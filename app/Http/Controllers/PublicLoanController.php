<?php

namespace App\Http\Controllers;

use App\Services\Landing\PublicService;
use Illuminate\Http\Request;

class PublicLoanController extends Controller
{
    public function index(PublicService $loans)
    {
        return $loans->AllLoans();
    }

    public function view(PublicService $loans, $id)
    {
        return $loans->SingleLoan($id);
    }
}
