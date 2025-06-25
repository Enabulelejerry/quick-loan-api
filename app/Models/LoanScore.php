<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanScore extends Model
{
    use HasFactory;
    protected $fillable = ['reason', 'score', 'loan_id'];
}
