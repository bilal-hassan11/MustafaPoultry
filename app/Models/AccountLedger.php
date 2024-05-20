<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;


class AccountLedger extends Model
{
    use HasFactory , DianujHashidsTrait;
    protected $table = 'account_ledger';

     // Specify the fields that are mass assignable
     protected $fillable = [
        'date',
        'account_id',
        'sale_chick_id',
        'purchase_chick_id',
        'sale_medicine_id',
        'return_medicine_id',
        'expire_medicine_id',
        'purchase_medicine_id',
        'sale_feed_id',
        'purchase_feed_id',
        'purchase_murghi_id',
        'sale_murghi_id',
        'general_purchase_id',
        'general_sale_id',
        'expense_id',
        'return_feed_id',
        'return_chick_id',
        'cash_id',
        'payment_id',
        'stock_adjustment_id',
        'debit',
        'credit',
        'description',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

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