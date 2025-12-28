<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * Generate QR Code on-the-fly as PNG
     * This doesn't require file storage
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        $data = $request->query('data');
        $size = $request->query('size', 300);
        
        if (!$data) {
            abort(400, 'Data parameter is required');
        }
        
        try {
            // Generate QR code as PNG
            $qrCode = QrCode::format('png')
                ->size($size)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($data);
            
            return response($qrCode)
                ->header('Content-Type', 'image/png')
                ->header('Cache-Control', 'public, max-age=31536000'); // Cache 1 year
        } catch (\Exception $e) {
            abort(500, 'Failed to generate QR code');
        }
    }
}
