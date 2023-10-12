<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name'];
   
    protected $hidden = array();

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
   
}