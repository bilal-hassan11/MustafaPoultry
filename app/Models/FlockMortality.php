<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;


class FlockMortality extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'flocks_mortality';

    // public function item(){
    //     return $this->belongsTo(Item::class, 'item_id', 'id');
    // }
}
