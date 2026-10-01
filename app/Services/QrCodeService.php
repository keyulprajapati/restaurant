<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate an SVG QR code string.
     */
    public static function generateSvg(string $text, int $size = 220): string
    {
        // Suppress PHP 8.4 deprecation notices from inner library
        $previousErrorReporting = error_reporting();
        error_reporting($previousErrorReporting & ~E_DEPRECATED);

        try {
            $svg = QrCode::format('svg')
                ->size($size)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($text);

            return (string) $svg;
        } catch (\Throwable $e) {
            // Fallback: minimal clean SVG if QR library throws
            return self::fallbackSvg($text, $size);
        } finally {
            error_reporting($previousErrorReporting);
        }
    }

    /**
     * Fallback placeholder SVG in case of unexpected environment errors.
     */
    protected static function fallbackSvg(string $text, int $size): string
    {
        $encoded = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 200 200">
            <rect width="200" height="200" fill="#f8f9fa" rx="12"/>
            <text x="50%" y="45%" text-anchor="middle" font-size="12" fill="#6c757d" font-family="sans-serif">Scan Table QR</text>
            <text x="50%" y="60%" text-anchor="middle" font-size="10" fill="#212529" font-family="monospace">'.$encoded.'</text>
        </svg>';
    }
}
