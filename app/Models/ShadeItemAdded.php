<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class ShadeItemAdded extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'shade_item_added';

    // public function item(){
    //     return $this->belongsTo(Item::class, 'item_id', 'id');
    // }
}
