<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    
    use HasFactory;
    function user(){
        return $this->belongsTo(User::class);
    }
    static public function getQualByCode($code){
        if($code == 'd'){
            return 'دبلوم';
        }else if($code == 'b'){
            return 'بكالوريوس';
    } else if($code == 'm'){
        return 'ماجستير';
    } else if($code == 'p'){
        return 'دكتوراه';
    }
}




    protected $guarded = [];
    protected $fillable = [
        'name',
        'phone',
        'email',
        'hire_date',
        'date_of_birth',
        'qual',
        'spec',
        'gender',
        'user_id',
    ];
}
