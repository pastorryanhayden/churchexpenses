<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cc_expenses()
    {
        return $this->hasMany(CcExpense::class);
    }

    public function income_splits()
    {
        return $this->hasMany(SplitIncome::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
