<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = 'Vendor';
}
