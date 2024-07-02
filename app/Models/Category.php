<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts()
    {
        return [
            'non_budget' => 'boolean',
        ];
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function split_incomes()
    {
        return $this->hasMany(SplitIncome::class);
    }

    public function split_expenses()
    {
        return $this->hasMany(SplitExpense::class);
    }

    public function getTotalAttribute()
    {
        if ($this->type == 'debit') {
            $expenses = $this->entries()->sum('debit_amount');
            $cc_expenses = $this->split_expenses()->sum('amount');
        } elseif ($this->type == 'credit') {
            $expenses = $this->entries()->sum('credit_amount');
            $cc_expenses = $this->split_incomes()->sum('amount');
        } elseif ($this->type == 'pass-through') {
            $standard_income = $this->entries()->sum('debit_amount');
            $split_income = $this->split_incomes()->sum('amount');
            $standard_expenses = $this->entries->sum('credit_amount');
            $split_expenses = $this->split_expenses()->sum('amount');

            return ($standard_income + $split_income) - ($standard_expenses + $split_expenses);
        }

        return $expenses + $cc_expenses;
    }
}
