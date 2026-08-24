<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'member_id',
        'invoice',
        'total',
        'payment_method'
    ];

    public function member() 
    {
        return $this->belongsTo(Member::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
