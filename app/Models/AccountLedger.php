<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;


class AccountLedger extends Model
{
    use HasFactory , DianujHashidsTrait;
    protected $table = 'account_ledger';

    // public function formulation_details(){
    //     return $this->hasMany(FormulationDetail::class, 'formulation_id', 'id');
    // }

    public function account(){
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    // public function sales(){
    //     return $this->belongsTo(SaleBook::class, 'sale_id', 'id');
    // }

    // public function purchases(){
    //     return $this->belongsTo(PurchaseBook::class, 'purchase_id', 'id');
    // }

    // public function item(){
    //     return $this->belongsTo(Item::class, 'sale_item_id', 'id');
    // }
}
