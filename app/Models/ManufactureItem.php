<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class ManufactureItem extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'manufacture_items';

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}