<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrGeneratorService
{
    public function generateForStudent(Student $student)
    {
        $qrCode = 'UNFV-' . $student->code . '-' . Str::random(10);
        
        $student->update(['qr_code' => $qrCode]);
        
        return $qrCode;
    }
    
    public function generateImage($qrCode, $size = 300)
    {
        return QrCode::size($size)
            ->format('png')
            ->generate($qrCode);
    }
}