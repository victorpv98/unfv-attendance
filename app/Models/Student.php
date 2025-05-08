<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'faculty_id', 'code', 'cycle', 'qr_code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot('semester')->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}