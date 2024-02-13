<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class merchantTransactionId extends Model
{
    //merchant_transaction_id it is related with hyper pay payment gateway 
    protected $table = 'merchant_transaction_ids';

    protected $fillable = ['marchant_transaction', 'user_id','resource_path'];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
