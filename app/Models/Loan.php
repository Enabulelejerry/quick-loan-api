<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['purpose', 'amount', 'loan_product_id', 'user_id', 'repayment_due', 'duration_days', 'interest_rate', 'status'];
    public function repaymentSchedules()
    {
        return $this->hasMany(RepaymentSchedule::class);
    }

    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public function loanScore()
    {
        return $this->hasOne(LoanScore::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
