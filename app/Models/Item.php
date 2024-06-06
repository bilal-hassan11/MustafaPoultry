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
}
