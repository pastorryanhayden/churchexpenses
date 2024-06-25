<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function credit_card_expenses()
    {
        return $this->hasMany(CcExpense::class);
    }

    public function getTotalAttribute()
    {
        if ($this->title != 'Credit Card Payment') {
            if ($this->type == 'debit') {
                $expenses = $this->expenses()->sum('debit_ammount');
                $cc_expenses = $this->credit_card_expenses()->sum('cost');
            } elseif ($this->type == 'credit') {
                $expenses = $this->expenses()->sum('credit_ammount');
                $cc_expenses = $this->credit_card_expenses()->sum('cost');
            }

            return $expenses + $cc_expenses;
        } else {
            return 0;
        }
    }
}
