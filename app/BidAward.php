<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BidAward extends Model
{
    protected $guarded = [];

    public  function disposal(){
    	return $this->belongsTo(AssetDisposal::class);
    }
}
