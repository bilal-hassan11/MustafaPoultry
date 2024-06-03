<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Item extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'items';
    protected $fillable = ['name'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function medicineInvoices()
    {
        return $this->hasMany(MedicineInvoice::class);
    }

    public function latestMedicineInvoice()
    {
        return $this->hasOne(MedicineInvoice::class)->latest();
    }

    public function feedInvoices()
    {
        return $this->hasMany(FeedInvoice::class);
    }

    public function latestFeedInvoice()
    {
        return $this->hasOne(FeedInvoice::class)->latest();
    }

    public function murghiInvoices()
    {
        return $this->hasMany(MurghiInvoice::class);
    }

    public function latestMurghiInvoice()
    {
        return $this->hasOne(MurghiInvoice::class)->latest();
    }

    public function chickInvoices()
    {
        return $this->hasMany(ChickInvoice::class);
    }

    public function latestChickInvoice()
    {
        return $this->hasOne(ChickInvoice::class)->latest();
    }

    public function otherInvoices()
    {
        return $this->hasMany(OtherInvoice::class);
    }

    public function latestOtherInvoice()
    {
        return $this->hasOne(OtherInvoice::class)->latest();
    }

    public function getLastPurchasePriceAttribute()
    {
        $latestMedicineInvoice = MedicineInvoice::where('item_id', $this->id)
            ->where('type', 'Purchase')
            ->latest()
            ->first();

        return $latestMedicineInvoice ? $latestMedicineInvoice->purchase_price : 1;
    }

    public function getLastPurchasePriceOfMurghiAttribute()
    {
        $latestMurghiInvoice = MurghiInvoice::where('item_id', $this->id)
            ->where('type', 'Purchase')
            ->latest()
            ->first();

        return $latestMurghiInvoice ? $latestMurghiInvoice->purchase_price : 1;
    }

    public function getLastPurchasePriceOfFeedAttribute()
    {
        $latestFeedInvoice = FeedInvoice::where('item_id', $this->id)
            ->where('type', 'Purchase')
            ->latest()
            ->first();

        return $latestFeedInvoice ? $latestFeedInvoice->purchase_price : 1;
    }

    public function getLastPurchasePriceOfChickAttribute()
    {
        $latestChickInvoice = ChickInvoice::where('item_id', $this->id)
            ->where('type', 'Purchase')
            ->latest()
            ->first();

        return $latestChickInvoice ? $latestChickInvoice->purchase_price : 1;
    }

    public function getLastPurchasePriceOfOtherAttribute()
    {
        $latestOtherInvoice = OtherInvoice::where('item_id', $this->id)
            ->where('type', 'Purchase')
            ->latest()
            ->first();

        return $latestOtherInvoice ? $latestOtherInvoice->purchase_price : 1;
    }

    public function getLastSalePriceAttribute()
    {
        $latestMedicineInvoice = MedicineInvoice::where('item_id', $this->id)
            ->where('type', 'Sale')
            ->latest()
            ->first();

        return $latestMedicineInvoice ? $latestMedicineInvoice->sale_price : 1;
    }

    public function getLastSalePriceOfMurghiAttribute()
    {
        $latestMurghiInvoice = MurghiInvoice::where('item_id', $this->id)
            ->where('type', 'Sale')
            ->latest()
            ->first();

        return $latestMurghiInvoice ? $latestMurghiInvoice->sale_price : 1;
    }

    public function getLastSalePriceOfFeedAttribute()
    {
        $latestFeedInvoice = FeedInvoice::where('item_id', $this->id)
            ->where('type', 'Sale')
            ->latest()
            ->first();

        return $latestFeedInvoice ? $latestFeedInvoice->sale_price : 1;
    }

    public function getLastSalePriceOfChickAttribute()
    {
        $latestChickInvoice = ChickInvoice::where('item_id', $this->id)
            ->where('type', 'Sale')
            ->latest()
            ->first();

        return $latestChickInvoice ? $latestChickInvoice->sale_price : 1;
    }


    public function getLastSalePriceOfOtherAttribute()
    {
        $latestOtherInvoice = OtherInvoice::where('item_id', $this->id)
            ->where('type', 'Sale')
            ->latest()
            ->first();

        return $latestOtherInvoice ? $latestOtherInvoice->sale_price : 1;
    }
}
