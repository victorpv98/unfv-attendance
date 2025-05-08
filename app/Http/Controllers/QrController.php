<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\QrGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrController extends Controller
{
    protected $qrService;

    public function __construct(QrGeneratorService $qrService)
    {
        $this->qrService = $qrService;
    }

    /**
     * Muestra el QR del estudiante autenticado
     */
    public function myQr()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'No se encontró información de estudiante asociada a tu cuenta.');
        }
        
        return view('students.my-qr', compact('student'));
    }
    
    /**
     * Muestra la imagen del QR de un estudiante
     */
    public function qrImage(Student $student)
    {
        if (!$student->qr_code) {
            $this->qrService->generateForStudent($student);
        }
        
        $qrImage = $this->qrService->generateImage($student->qr_code);
        
        return response($qrImage)
            ->header('Content-Type', 'image/png');
    }
    
    /**
     * Regenera el código QR para el estudiante
     */
    public function regenerateQr(Request $request)
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'No se encontró información de estudiante asociada a tu cuenta.');
        }
        
        $this->qrService->generateForStudent($student);
        
        return redirect()->route('students.my-qr')
            ->with('success', 'Código QR regenerado exitosamente.');
    }
}