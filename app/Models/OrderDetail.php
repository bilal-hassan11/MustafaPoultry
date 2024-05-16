<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class  OrderDetail extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'order_detail';

    public function sku_detail(){
    
        return $this->belongsTo(Sku::class, 'sku_id', 'id');
    
    }

    public function customer_detail(){
    
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id', 'id');
    
    }

}
