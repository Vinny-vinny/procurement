<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Walla extends Model
{
    protected $guarded = [];
    protected $table = 'machines';
    protected $connection = 'mysql3';


}
