<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DianujHashidsTrait;

class Account extends Model
{
    use HasFactory, DianujHashidsTrait;

    protected $table = 'accounts';

    public function grand_parent()
    {
        return $this->belongsTo(AccountType::class, 'grand_parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(AccountType::class, 'parent_id', 'id');
    }

    public function accountLedgers()
    {
        return $this->hasMany(AccountLedger::class, 'account_id');
    }

    public function getBalance($date)
    {
        $sumDebit = $this->accountLedgers()
            ->where('account_id', $this->id)
            ->whereDate('date', '<', $date)
            ->sum('debit');

        $sumCredit = $this->accountLedgers()
            ->where('account_id', $this->id)
            ->whereDate('date', '<', $date)
            ->sum('credit');

        return $sumDebit - $sumCredit;
    }
}
