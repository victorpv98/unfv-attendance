<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'faculty_id', 'credits', 'cycle'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student')
                    ->withPivot('semester')
                    ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}