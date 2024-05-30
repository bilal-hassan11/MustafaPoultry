<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Account extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'accounts';

    public function grand_parent(){
        return $this->belongsTo(AccountType::class, 'grand_parent_id', 'id');
    }

    public function parent(){
        return $this->belongsTo(AccountType::class, 'parent_id', 'id');
    }
}
