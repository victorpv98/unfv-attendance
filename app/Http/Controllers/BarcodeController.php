<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\BarcodeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarcodeController extends Controller
{
    protected $barcodeService;

    public function __construct(BarcodeGeneratorService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    /**
     * Muestra el código de barras del estudiante autenticado
     */
    public function myBarcode()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'No se encontró información de estudiante asociada a tu cuenta.');
        }
        
        return view('students.my-barcode', compact('student'));
    }
    
    /**
     * Muestra la imagen del código de barras de un estudiante
     */
    public function barcodeImage(Student $student)
    {
        if (!$student->qr_code) { // Mantén el nombre del campo por compatibilidad
            $this->barcodeService->generateForStudent($student);
        }
        
        $barcodeImage = $this->barcodeService->generateImage($student->qr_code);
        
        return response($barcodeImage)
            ->header('Content-Type', 'image/png');
    }
    
    /**
     * Regenera el código de barras para el estudiante
     */
    public function regenerateBarcode(Request $request)
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'No se encontró información de estudiante asociada a tu cuenta.');
        }
        
        $this->barcodeService->generateForStudent($student);
        
        return redirect()->route('students.my-barcode')
            ->with('success', 'Código de barras regenerado exitosamente.');
    }
}