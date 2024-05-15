<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Formulation extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'formulations';

    public function formulation_details(){
        return $this->hasMany(FormulationDetail::class, 'formulation_id', 'id');
    }

    public function item(){
        return $this->belongsTo(Item::class, 'sale_item_id', 'id');
    }
}
