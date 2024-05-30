<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class CashBook extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'cash_book';


    public function account(){
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }
}
