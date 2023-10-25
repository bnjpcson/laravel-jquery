<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    //
    protected $fillable = ['std_name', 'std_address', 'std_contactno'];
}
