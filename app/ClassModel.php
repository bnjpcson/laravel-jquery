<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    //
    protected $fillable = ['section', 'teacher'];
    protected $table = 'classrooms';

    public function getClassroom()
    {
        return $this->hasMany('App\Classroom_Student', 'classroom_id', 'id');
    }
}
