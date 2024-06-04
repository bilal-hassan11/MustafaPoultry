<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\StockTrait;

class MurghiInvoice extends Model
{
    use HasFactory, SoftDeletes, StockTrait;
    protected $table = "murghi_invoices";
    protected $fillable = [
        'date',
        'invoice_no',
        'account_id',
        'ref_no',
        'description',
        'item_id',
        'purchase_price',
        'sale_price',
        'quantity', // Quantity is weight
        'amount',
        'weight_detection',
        'final_weight',
        'net_amount',
        'expiry_date',
        'type',
        'stock_type',
        'is_draft',
        'whatsapp_status',
        'remarks',
    ];

    /**
     * Get the account associated with the murghi invoice.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the item associated with the murghi invoice.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
