<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;


class OthersDc extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'others_dc';


    public function  account(){
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function  item(){
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
