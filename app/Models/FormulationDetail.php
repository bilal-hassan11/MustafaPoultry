<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;
class   FormulationDetail extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'formulation_details';

    public function item(){
        return $this->belongsTo(Item::class, 'purhcase_item_id', 'id');
    }
}
