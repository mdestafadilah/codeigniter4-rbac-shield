<?php

/**
 * Image Processing Service
 * source:  https://chatgpt.com/s/t_69374986405c81919cbfa7abfa4edf5f
 *          https://chatgpt.com/s/t_69374b02303881918e9f38968d3af9f7
 *          https://chatgpt.com/s/t_69374b02303881918e9f38968d3af9f7
 */

namespace App\Libraries;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        // memakai GD (lebih ringan dibanding Imagick)
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Process banner lengkap:
     * - Resize max width
     * - Auto rotate
     * - Crop rasio 19:6
     * - Generate multi-size
     * - Convert WEBP dengan compress 50–70%
     * - Return info JSON (original vs optimized)
     */
    public function processBanner($file, $savePath, $maxWidth = 2560, $quality = 60)
    {
        // Buat folder jika belum ada
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }

        // Nama dasar
        $baseName = uniqid('banner_');

        // Hitung size asli
        $originalSize = filesize($file->getTempName());

        // Load image
        $img = $this->manager->read($file->getTempName());

        // Auto rotate jika portrait
        if ($img->height() > $img->width()) {
            $img = $img->rotate(90);
        }

        // Limit max width
        if ($img->width() > $maxWidth) {
            $img = $img->resize($maxWidth, null, function ($c) {
                $c->aspectRatio();
            });
        }

        // Update ukuran
        $width  = $img->width();
        $height = $img->height();

        // Rasio target 19:6
        $targetRatio = 19 / 6;
        $currentRatio = $width / $height;

        // Tentukan area crop
        if ($currentRatio > $targetRatio) {
            $newWidth = intval($height * $targetRatio);
            $newHeight = $height;
            $x = intval(($width - $newWidth) / 2);
            $y = 0;
        } else {
            $newWidth = $width;
            $newHeight = intval($width / $targetRatio);
            $x = 0;
            $y = intval(($height - $newHeight) / 2);
        }

        // Crop final
        $img = $img->crop($newWidth, $newHeight, $x, $y);

        // ---- MULTI SIZE ---- //
        $sizes = [
            'thumb'  => 400,
            'medium' => 1280,
            'hd'     => 1920,
            'full'   => $newWidth,
        ];

        $savedFiles = [];
        $optimizedSizes = [];

        foreach ($sizes as $key => $targetWidth) {

            $clone = clone $img;

            if ($targetWidth < $newWidth) {
                // Resize menjaga rasio
                $clone = $clone->resize($targetWidth, null, function ($c) {
                    $c->aspectRatio();
                });
            }

            // Nama file
            $fileName = "{$baseName}_{$key}.webp";
            $fullPath = $savePath . $fileName;

            // Simpan sebagai WebP dengan kualitas aggressive
            $clone->toWebp($quality)->save($fullPath);

            // Hitung size file
            $optimizedSizes[$key] = filesize($fullPath);

            // Kumpulkan nama file
            $savedFiles[$key] = $fileName;
        }

        // Total ukuran hasil optimasi
        $totalOptimized = array_sum($optimizedSizes);

        // Hitung penghematan
        $savedBytes = $originalSize - $totalOptimized;
        $percentSaved = $savedBytes > 0 ? ($savedBytes / $originalSize) * 100 : 0;

        // Return JSON-style info
        return [
            'files' => $savedFiles,
            'info' => [
                'original_size' => $originalSize,
                'optimized_sizes' => $optimizedSizes,
                'saved_bytes' => $savedBytes,
                'saved_percent' => round($percentSaved, 2)
            ]
        ];
    }
}
