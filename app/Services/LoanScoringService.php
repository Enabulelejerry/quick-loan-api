<?php


namespace App\Services;

use App\Models\Loan;
use App\Models\LoanScore;

class LoanScoringService
{
    public function score(Loan $loan)
    {
        // Rule-based scoring logic
        if ($loan->amount <= 20000) {
            $score = 85;
            $reason = "Low amount – High eligibility";
        } elseif ($loan->amount <= 50000) {
            $score = 65;
            $reason = "Moderate amount – Medium risk";
        } else {
            $score = 45;
            $reason = "High risk due to amount";
        }

        LoanScore::create([
            'loan_id' => $loan->id,
            'score' => $score,
            'reason' => $reason,
        ]);
    }
}
