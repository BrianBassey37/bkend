<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name'];
   
    protected $hidden = array();

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function dept()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }
   
}