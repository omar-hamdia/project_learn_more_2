<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'stage_id'];
    function stage()
    {
        return $this->belongsTo(Stage::class);
    }
    public static function getStatusByCod($status){
        if($status == 1){
            return 'active';
    }
    return 'inactive';
    }
}
