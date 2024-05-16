<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Payment extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'payment_book';

   
    public function account(){
        return $this->belongsTo(Account::class, 'creditor_account_id', 'id');
    }

    public function d_account(){
        return $this->belongsTo(Account::class, 'debtor_account_id', 'id');
    }

}
