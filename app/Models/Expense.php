<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Expense extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'expenses';
    
     public function category(){
        return $this->belongsTo(ExpenseCategory::class, 'category_id', 'id');
    }
}
