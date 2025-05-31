<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];
    public function grade(){
        return $this->belongsTo(Grade::class);
    }
     public function section(){
        return $this->belongsTo(Section::class);
    }
    public function user() {
    return $this->belongsTo(User::class);
}
protected $fillable = [
    'first_name',
    'last_name',
    'parent_name',
    'parent_phone',
    'gender',
    'date_of_birth',
    'grade_id',
    'section_id',
    'user_id',
];

}
