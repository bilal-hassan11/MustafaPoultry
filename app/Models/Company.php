<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Company extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'companies';

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}