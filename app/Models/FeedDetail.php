<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;


class FeedDetail extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'feed_detail';

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function getfeed(){
        return $this->belongsTo(PurchaseFeed::class, 'feed_det_id', 'id');
    }
}
