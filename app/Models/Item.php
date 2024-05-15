<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Item extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'items';

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
