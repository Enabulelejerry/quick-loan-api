<?php

namespace App\Services\Landing;

use App\Models\LoanProduct;

class PublicService
{

    public function AllLoans()
    {
        $products = LoanProduct::select('id', 'name', 'description', 'max_amount', 'interest_rate', 'duration_days', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'loan_products' => $products
        ]);
    }

    public function SingleLoan($id)
    {
        $product = LoanProduct::where('id', $id)
            ->select('id', 'name', 'description', 'max_amount', 'interest_rate', 'duration_days', 'created_at')
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'loan_product' => $product
        ]);
    }
}
