<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bidding extends Model
{
    protected $guarded = [];

    public function user(){
    	return $this->belongsTo(User::class);
    }
    public function disposal(){
    	return $this->belongsTo(AssetDisposal::class);
    }
    public function payment_mode(){
    	return $this->belongsTo(PaymentMode::class);
    }
}
