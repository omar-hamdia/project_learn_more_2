<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    // أضفنا status و tag للحقلين القابلين للملء جماعياً
    protected $fillable = [
        'name',
        'stage_id',
        'status',
        'tag',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public static function getStatusByCode($status)
    {
        return $status == '1' ? 'active' : 'inactive';
    }
}
