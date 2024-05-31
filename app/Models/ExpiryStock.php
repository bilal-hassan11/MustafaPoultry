<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpiryStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'medicine_invoice_id',
        'item_id',
        'rate',
        'quantity',
        'expiry_date',
    ];

    /**
     * Get the medicine invoice associated with the expiry stock.
     */
    protected $appends = ['average_price'];

    public function getAveragePriceAttribute()
    {
        if ($this->quantity == 0) {
            return 0;
        }

        return $this->rate / $this->quantity;
    }

    public function medicineInvoice()
    {
        return $this->belongsTo(MedicineInvoice::class);
    }

    /**
     * Get the item associated with the expiry stock.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
