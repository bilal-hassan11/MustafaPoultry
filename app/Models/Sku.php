<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Sku extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $fillable = ['name','sequence_no'];

    protected $table = 'sku';
}