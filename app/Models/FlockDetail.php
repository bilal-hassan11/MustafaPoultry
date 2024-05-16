<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class FlockDetail extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'flock_detail';

    // public function shade(){
    //     return $this->belongsTo(Flock::class, 'shade_id', 'id');
    // }
}
