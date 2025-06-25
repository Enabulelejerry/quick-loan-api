<?php

namespace App\Http\Controllers;

use Yabacon\Paystack;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function initialize(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric',
            'repayment_schedule_id' => 'required|exists:repayment_schedules,id',
        ]);

        $paymentData = [
            'amount' => $validated['amount'] * 100,
            'email' => $validated['email'],
            'metadata' => [
                'repayment_schedule_id' => $validated['repayment_schedule_id'],
                'user_id' => auth()->id(),
            ],
        ];

        $paystack = new Paystack(config('paystack.secretKey'));
        $transaction = $paystack->transaction->initialize($paymentData);
        return response()->json([
            'authorization_url' => $transaction->data->authorization_url,
            'access_code' => $transaction->data->access_code,
            'reference' => $transaction->data->reference,
        ]);
    }

    public function verify(Request $request)
    {
        $reference = $request->query('reference');

        $paystack = new Paystack(config('paystack.secretKey'));

        try {
            $transaction = $paystack->transaction->verify(['reference' => $reference]);

            if ($transaction->data->status === 'success') {
                $scheduleId = $transaction->data->metadata->repayment_schedule_id;

                DB::table('repayment_schedules')
                    ->where('id', $scheduleId)
                    ->update(['status' => 'paid']);

                // return redirect('/user/payments/success');
                return response()->json(['message' => 'Payment verified and schedule updated.']);
            }

            // return redirect('/user/payments/failed');
            return response()->json(['error' => 'Payment not successful'], 400);
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
