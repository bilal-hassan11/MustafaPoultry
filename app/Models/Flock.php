<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Flock extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'flocks';

    public function shade(){
        return $this->belongsTo(Flock::class, 'shade_id', 'id');
    }
}
