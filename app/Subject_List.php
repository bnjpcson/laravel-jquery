<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject_List extends Model
{
    //
    protected $fillable = ['classroom_id', 'subject_id', 'description'];
    protected $table = 'subject_list';
}
