<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function split_expenses()
    {
        return $this->hasMany(SplitExpense::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->entries()->sum('debit_amount') + $this->split_expenses()->sum('amount');
    }
}
