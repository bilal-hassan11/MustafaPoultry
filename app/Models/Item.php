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

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function sale_feed()
    {
        return $this->hasMany(SaleFeed::class);
    }
    
    public function purchase_feed()
    {
        return $this->hasMany(PurchaseFeed::class);
    }

    public function return_feed()
    {
        return $this->hasMany(ReturnFeed::class);
    }

    public function medicineInvoices()
    {
        return $this->hasMany(MedicineInvoice::class);
    }

    public function latestMedicineInvoice()
    {
        return $this->hasOne(MedicineInvoice::class)->latest();
    }

}