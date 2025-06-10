<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Str;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeGeneratorService
{
    /**
     * Genera un nuevo código de barras para un estudiante
     */
    public function generateForStudent(Student $student)
    {
        // Generamos un código numérico único para el estudiante (códigos de barras funcionan mejor con números)
        $barcodeCode = 'UNFV' . $student->code . rand(1000, 9999);
        
        // Actualizamos el código del estudiante
        $student->update(['qr_code' => $barcodeCode]); // Mantén el nombre del campo por compatibilidad
        
        return $barcodeCode;
    }
    
    /**
     * Genera la imagen del código de barras
     */
    public function generateImage($barcodeCode, $type = 'TYPE_CODE_128')
    {
        $generator = new BarcodeGeneratorPNG();
        
        // Generar código de barras tipo CODE 128 (más común y compatible)
        return $generator->getBarcode($barcodeCode, $generator::TYPE_CODE_128, 3, 50);
    }
}