<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Shade extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'shades';

    public function staff(){
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function flock(){
        return $this->belongsTo(Flock::class, 'flock_id', 'id');
    }
}
