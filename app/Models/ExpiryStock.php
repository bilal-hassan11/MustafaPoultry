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

    protected $appends = ['average_price'];

    public function getAveragePriceAttribute()
    {
        if ($this->quantity == 0) {
            return 0;
        }

        return $this->rate / $this->quantity;
    }
    
    /**
     * Get the medicine invoice associated with the expiry stock.
     */
    public function medicineInvoice()
    {
        return $this->belongsTo(MedicineInvoice::class);
    }

    public function feedInvoice()
    {
        return $this->belongsTo(FeedInvoice::class);
    }

    public function murghiInvoice()
    {
        return $this->belongsTo(MurghiInvoice::class);
    }

    public function chickInvoice()
    {
        return $this->belongsTo(ChickInvoice::class);
    }

    public function otherInvoice()
    {
        return $this->belongsTo(OtherInvoice::class);
    }

    /**
     * Get the item associated with the expiry stock.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function latestMedicineInvoice()
    {
        return $this->hasOne(MedicineInvoice::class,'item_id')->latest();
    }

    public function latestFeedInvoice()
    {
        return $this->hasOne(FeedInvoice::class,'item_id')->latest();
    }

    public function latestMurghiInvoice()
    {
        return $this->hasOne(MurghiInvoice::class,'item_id')->latest();
    }

    public function latestChickInvoice()
    {
        return $this->hasOne(ChickInvoice::class,'item_id')->latest();
    }

    public function latestOtherInvoice()
    {
        return $this->hasOne(OtherInvoice::class,'item_id')->latest();
    }

}