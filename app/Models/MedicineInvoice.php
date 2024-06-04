<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\StockTrait;

class MedicineInvoice extends Model
{
    use HasFactory, SoftDeletes, StockTrait;
    protected $table = "medicine_invoices";

    protected $fillable = [
        'date',
        'invoice_no',
        'account_id',
        'ref_no',
        'description',
        'item_id',
        'purchase_price',
        'sale_price',
        'quantity',
        'amount',
        'discount_in_rs',
        'discount_in_percent',
        'total_cost',
        'net_amount',
        'expiry_date',
        'type',
        'stock_type',
        'is_draft',
        'whatsapp_status',
        'remarks',
    ];

    /**
     * Get the account associated with the medicine invoice.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the item associated with the medicine invoice.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
