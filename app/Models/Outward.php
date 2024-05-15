<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Outward extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'outward';

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function account(){
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    // public function outward_detail(){
    //     return $this->belongsTo(OutwardDetail::class, 'id', 'outward_id');
    // }
    public function outardDetails(){
        return $this->hasMany(OutwardDetail::class, 'outward_id', 'id');
    }
}
