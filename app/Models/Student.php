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
        return $this->belongsToMany(Course::class, 'course_student')
                    ->withPivot('semester')
                    ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($student) {
            if (empty($student->qr_code)) {
                $student->qr_code = $student->generateQrCode();
            }
        });
    }

    private function generateQrCode()
    {
        return 'QR-' . $this->code;
        
        return \Illuminate\Support\Str::uuid();
        
        return 'QR-' . \Illuminate\Support\Str::random(10);
    }
}