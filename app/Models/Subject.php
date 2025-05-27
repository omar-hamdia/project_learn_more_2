<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Teacher;
use App\Models\Grade;
class Subject extends Model
{
    use HasFactory;
    public function teacher() {
    return $this->belongsTo(Teacher::class);
}
public function grade() {
    return $this->belongsTo(Grade::class, 'grade_id');
}
protected $fillable = [
        'title',
        'teacher_id',
        'grade_id',
        'book',
        // أضف أي أعمدة أخرى تستخدمها في الإنشاء
    ];
}
