<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class OutwardDetail extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'outward_detail';

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function outward_detail(){
        return $this->belongsTo(Outward::class, 'id', 'outward_id');
    }
}
