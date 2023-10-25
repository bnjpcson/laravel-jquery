<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom_Student extends Model
{
    //
    protected $fillable = ['classroom_id', 'student_id'];
    protected $table = 'classroom_student';

    public function students()
    {
        return $this->hasMany(Students::class);
    }
}
