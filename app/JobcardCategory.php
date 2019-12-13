<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobcardCategory extends Model
{
    protected $guarded = [];
    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleting(function ($cat){
            $relationsMethods = ['category'];
            foreach ($relationsMethods as $relationsMethod){
                if ($cat->$relationsMethod()->count() > 0){
                    return false;
                }
            }
        });
    }
    public function category()
    {
        return $this->belongsTo(JobcardCategory::class,'id');
    }
}
