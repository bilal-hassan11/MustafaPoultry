<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountLedger extends Model
{
    use HasFactory, SoftDeletes;

    // Table associated with the model
    protected $table = 'account_ledger';

    // The attributes that are mass assignable
    protected $fillable = [
        'account_id',
        'type',
        'date',
        'medicine_invoice_id',
        'chick_invoice_id',
        'murghi_invoice_id',
        'feed_invoice_id',
        'other_invoice_id',
        'cash_id',
        'payment_id',
        'expense_id',
        'debit',
        'credit',
        'narration',
        'description',
    ];

    // The attributes that should be mutated to dates
    protected $dates = ['deleted_at'];

    // Define any relationships if necessary
    // Example: A ledger might belong to an account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Example: If there are related invoices
    public function medicineInvoice()
    {
        return $this->belongsTo(MedicineInvoice::class);
    }

    public function chickInvoice()
    {
        return $this->belongsTo(ChickInvoice::class);
    }

    public function murghiInvoice()
    {
        return $this->belongsTo(MurghiInvoice::class);
    }

    public function feedInvoice()
    {
        return $this->belongsTo(FeedInvoice::class);
    }

    public function otherInvoice()
    {
        return $this->belongsTo(OtherInvoice::class);
    }

    public function cash()
    {
        return $this->belongsTo(CashBook::class, 'cash_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
