<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts()
    {
        return [
            'split' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cc_expenses()
    {
        return $this->hasMany(CcExpense::class);
    }

    public function split_incomes()
    {
        return $this->hasMany(SplitIncome::class);
    }

    public function split_expenses()
    {
        return $this->hasMany(SplitExpense::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
