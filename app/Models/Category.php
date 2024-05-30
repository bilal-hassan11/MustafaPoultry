<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Category extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'categories';

    public function companies(){
        return $this->hasMany(Company::class, 'category_id', 'id');
    }

    public function items(){
        return $this->hasMany(Item::class, 'category_id', 'id');
    }
}
