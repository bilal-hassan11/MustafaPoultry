<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class SaleMedicineDetail extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'sale_medicine_details';

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
