<?php

namespace App\Services\Admin;

use App\Models\LoanProduct;
use Exception;

class LoanService
{

    public function storeLoan($request)
    {

        try {
            $validated = $request->validated();

            $product = LoanProduct::create($validated);
            return response()->json(
                [
                    'message' => 'Loan create',
                    'product' => $product
                ]
            );
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to create loan",
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function allLoan()
    {
        $loans = LoanProduct::all();
        return response()->json(
            [

                'LoanProducts' => $loans
            ]
        );
    }

    public function singleLoan($id)
    {
        $loan = LoanProduct::find($id);
        if (!$loan) {
            return response()->json(['message' => 'loan product not found'], 404);
        }
        return response()->json(['loan' => $loan]);
    }

    public function UpdateLoan($request, $id)
    {
        $loan = LoanProduct::find($id);
        if (!$loan) {
            return response()->json(['message' => 'loan product not found'], 404);
        }

        try {
            $validated = $request->validated();

            $product = $loan->update($validated);
            return response()->json(
                [
                    'message' => 'Loan updated',
                    'product' => $product
                ]
            );
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to update loan",
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
