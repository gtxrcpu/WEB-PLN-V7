<?php

namespace App\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeHelper
{
    /**
     * Generate QR Code as base64 data URI (PNG format)
     * This doesn't require file storage and works on any hosting
     * 
     * @param string $data The data to encode in QR
     * @param int $size Size of QR code (default 300)
     * @return string Base64 data URI
     */
    public static function generateDataUri(string $data, int $size = 300): string
    {
        try {
            // Generate QR code as PNG and encode to base64
            $qrCode = QrCode::format('png')
                ->size($size)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($data);
            
            // Convert to base64 data URI
            $base64 = base64_encode($qrCode);
            return 'data:image/png;base64,' . $base64;
        } catch (\Exception $e) {
            // Fallback: return placeholder
            return 'data:image/svg+xml;base64,' . base64_encode(
                '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300"><rect width="300" height="300" fill="#f0f0f0"/><text x="150" y="150" text-anchor="middle" fill="#999">QR Error</text></svg>'
            );
        }
    }
    
    /**
     * Generate QR Code as SVG data URI
     * Smaller file size than PNG
     * 
     * @param string $data The data to encode in QR
     * @param int $size Size of QR code (default 300)
     * @return string SVG data URI
     */
    public static function generateSvgDataUri(string $data, int $size = 300): string
    {
        try {
            $qrCode = QrCode::format('svg')
                ->size($size)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($data);
            
            // Encode SVG to base64 data URI
            $base64 = base64_encode($qrCode);
            return 'data:image/svg+xml;base64,' . $base64;
        } catch (\Exception $e) {
            return 'data:image/svg+xml;base64,' . base64_encode(
                '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300"><rect width="300" height="300" fill="#f0f0f0"/><text x="150" y="150" text-anchor="middle" fill="#999">QR Error</text></svg>'
            );
        }
    }
}
