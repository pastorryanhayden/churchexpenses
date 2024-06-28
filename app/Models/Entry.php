<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts()
    {
        return [
            'split' => 'boolean',
            'date' => 'date',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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

    public function getTypeAttribute()
    {
        if ($this->credit_amount > 0) {
            return 'credit';
        } else {
            return 'debit';
        }

    }
}
